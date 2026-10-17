-- Progressive-profiling redesign: minimal "Stage 1" intake fields collected
-- within the first 30-60 seconds after sign-up, plus a single flexible JSON
-- column for the category-specific health/safety questionnaire collected
-- later, just before a client's first booking. A JSON blob avoids dozens of
-- mostly-null columns since the question set varies heavily per category
-- (child/teenager/adult/senior/pregnant/postpartum) — same pattern already
-- used by client_profiles.preferences.

-- NOTE: `date_of_birth` already exists on the live dev DB (added outside of
-- any tracked migration, predating this file — column order in DESCRIBE
-- shows it directly after user_id rather than after age). Not re-added here
-- since column position has no functional effect on associative-array
-- hydration; schema.sql below documents it in this migration's intended
-- position for fresh installs.
ALTER TABLE client_profiles
    ADD COLUMN height_cm SMALLINT UNSIGNED NULL AFTER age,
    ADD COLUMN weight_kg SMALLINT UNSIGNED NULL AFTER height_cm,
    ADD COLUMN city VARCHAR(100) NULL AFTER country,
    ADD COLUMN emergency_contact_relationship VARCHAR(50) NULL AFTER emergency_contact_phone,
    ADD COLUMN client_category ENUM('child', 'teenager', 'adult', 'senior_citizen', 'pregnant_woman', 'postpartum_mother') NULL AFTER emergency_contact_relationship,
    ADD COLUMN health_assessment JSON NULL AFTER client_category;
