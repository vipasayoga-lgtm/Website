<?php

/**
 * Every question the client has answered across the progressive-profiling
 * flow, rendered from the stored `client_profiles.health_assessment` JSON.
 *
 * Deliberately generic: it walks whatever topics/fields are in the JSON rather
 * than holding its own copy of the question list. The wording lives in the
 * onboarding views (e.g. CATEGORY_QUESTIONS in onboarding/health-assessment.php)
 * and field keys are humanised here, so adding a question to any step shows up
 * on this page with no change — nothing to keep in sync, which is the whole
 * point given the view/controller whitelist pair already needs watching.
 *
 * @var array|null $assessment Decoded health_assessment JSON.
 * @var string|null $heading   Optional section heading override.
 */

use App\Core\View;

$assessment = is_array($assessment ?? null) ? $assessment : [];

/* Bookkeeping written by the auto-save endpoint, not answers. */
$skipKeys = ['currentStep', 'stage'];

/* Stage 2 runs before a client's first booking; the rest are later stages. */
$sectionTitles = [
    'medicalHistory' => 'Medical conditions',
    'healthScreening' => 'Health screening',
    'painAssessment' => 'Pain assessment',
    'doctorRestrictions' => 'Doctor restrictions',
    'categorySafety' => 'Category-specific questions',
    'lifestyle' => 'Lifestyle',
    'sleep' => 'Sleep',
    'exercise' => 'Exercise',
    'stress' => 'Stress',
    'yogaExperience' => 'Yoga experience',
    'goals' => 'Yoga goals',
    'supportSystem' => 'Support system',
    'recoveryGoals' => 'Recovery goals',
    'advancedHealth' => 'Health update',
    'medication' => 'Medication',
    'surgeries' => 'Surgeries',
    'advancedPain' => 'Pain update',
    'fallRisk' => 'Fall risk',
    'lifestylePreferences' => 'Lifestyle preferences',
    'fitnessBackground' => 'Fitness background',
    'foodPreference' => 'Food preference',
    'communicationPreference' => 'Communication preference',
    'languagePreference' => 'Language preference',
    'longTermGoals' => 'Long-term goals',
    'consent' => 'Consent',
];

/* Which topics were collected before the first booking — worth marking, since
   an admin checking safety cares that these existed pre-session. */
$preBookingSections = ['medicalHistory', 'healthScreening', 'painAssessment', 'doctorRestrictions', 'categorySafety'];

/** guardianPresent → "Guardian present"; none_of_these → "None of these". */
$humanise = static function (string $key): string {
    $spaced = preg_replace('/(?<=[a-z0-9])(?=[A-Z])/', ' ', str_replace('_', ' ', $key));

    return ucfirst(strtolower(trim((string) $spaced)));
};

/* Stored option values whose humanised form is unreadable ("lt_1y" → "Lt 1y").
   Only the coded ones need an entry — everything else humanises fine, so this
   stays a short list rather than a parallel copy of every option label. */
$valueLabels = [
    'lt_1y' => 'Less than 1 year',
    '1_5y' => '1–5 years',
    '5y_plus' => '5+ years',
    'none_of_these' => 'None of these',
    'no_pain' => 'No pain',
];

$formatValue = static function ($value) use ($humanise, $valueLabels) {
    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }
    if ($value === null || $value === '' || $value === []) {
        return '—';
    }
    if (is_array($value)) {
        return implode(', ', array_map(
            static fn ($v) => is_bool($v) ? ($v ? 'Yes' : 'No') : ($valueLabels[(string) $v] ?? $humanise((string) $v)),
            $value
        ));
    }
    if (is_numeric($value)) {
        return (string) $value;
    }

    return $valueLabels[(string) $value] ?? $humanise((string) $value);
};

/** One field row, recursing into nested detail maps (per-condition, per-area). */
$renderRows = static function (array $data, int $depth = 0) use (&$renderRows, $humanise, $formatValue): string {
    $html = '';

    foreach ($data as $key => $value) {
        $label = $humanise((string) $key);

        // A map of maps (medicalHistory.details, painAssessment.details) — one
        // labelled sub-block per condition/area rather than a flattened row.
        $isNestedMap = is_array($value) && $value !== [] && !array_is_list($value)
            && count(array_filter($value, 'is_array')) === count($value);

        if ($isNestedMap && $depth < 2) {
            foreach ($value as $subKey => $subValue) {
                $inner = $renderRows(is_array($subValue) ? $subValue : [$subKey => $subValue], $depth + 1);
                /* A selected condition/pain area whose follow-up screen was
                   never completed has an empty detail map — say so rather than
                   render an empty box that reads as a broken layout. */
                if ($inner === '') {
                    $inner = '<div class="qa-row"><span class="qa-row__q">Follow-up</span>'
                        . '<span class="qa-row__a">Not answered yet</span></div>';
                }
                $html .= '<div class="qa-block">'
                    . '<div class="qa-block__title">' . View::e($humanise((string) $subKey)) . '</div>'
                    . $inner
                    . '</div>';
            }
            continue;
        }

        if (is_array($value) && !array_is_list($value) && $depth < 2) {
            $html .= '<div class="qa-block">'
                . '<div class="qa-block__title">' . View::e($label) . '</div>'
                . $renderRows($value, $depth + 1)
                . '</div>';
            continue;
        }

        $html .= '<div class="qa-row">'
            . '<span class="qa-row__q">' . View::e($label) . '</span>'
            . '<span class="qa-row__a">' . View::e($formatValue($value)) . '</span>'
            . '</div>';
    }

    return $html;
};

/* Only topics that actually hold answers — an empty topic would otherwise
   render as a heading with nothing under it. */
$sections = [];
foreach ($assessment as $topic => $answers) {
    if (in_array($topic, $skipKeys, true) || !is_array($answers) || $answers === []) {
        continue;
    }
    $sections[$topic] = $answers;
}

/* Known topics first, in flow order, then anything unrecognised so a newly
   added step is never silently dropped from this page. */
$ordered = [];
foreach (array_keys($sectionTitles) as $known) {
    if (isset($sections[$known])) {
        $ordered[$known] = $sections[$known];
    }
}
foreach ($sections as $topic => $answers) {
    $ordered[$topic] ??= $answers;
}
?>
<h2 class="mb-2"><?= View::e($heading ?? 'Assessment answers') ?></h2>

<?php if (empty($ordered)): ?>
<p class="text-muted mb-8">This client hasn't answered any assessment questions yet.</p>
<?php else: ?>
<p class="text-muted mb-4" style="font-size:var(--font-size-sm)">
    Every question this client has answered. Sections marked
    <span class="badge badge-pending">Pre-booking</span> were answered before their first session was allowed.
</p>

<div class="qa-sections mb-8">
    <?php foreach ($ordered as $topic => $answers): ?>
    <section class="card qa-section">
        <div class="qa-section__head">
            <h3 class="qa-section__title"><?= View::e($sectionTitles[$topic] ?? ucfirst($humanise($topic))) ?></h3>
            <?php if (in_array($topic, $preBookingSections, true)): ?>
            <span class="badge badge-pending">Pre-booking</span>
            <?php endif; ?>
        </div>
        <?= $renderRows($answers) ?>
    </section>
    <?php endforeach; ?>
</div>
<?php endif; ?>
