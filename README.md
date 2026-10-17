# Vipasa Yoga — Booking Platform

A yoga appointment booking SaaS: public marketing site (landing, services,
instructors, gallery, legal pages), a client booking flow (browse → choose
instructor → pick a slot → pay via Razorpay or Stripe test mode), a client
dashboard (bookings, payment history, invoices), a trainer dashboard, and a
full admin panel (dashboard, bookings, calendar, packages, instructors,
customers, payments, reports with CSV/PDF/Excel export, settings,
notifications, activity log). Plain PHP 8 MVC (no framework) + MySQL/MariaDB +
HTML5/CSS3/vanilla ES6 JS, with two Composer libraries (Dompdf,
PhpSpreadsheet) vendored in for report export — see `docs/architecture.md`
for the full design.

**Design system**: Vipasa Blue (`#14425E`/`#2D6A88`) as the primary brand
color, Saffron (`#E8963C`) reserved strictly for primary CTAs (Book Now, Pay
Now, Continue — never navigation/icons/backgrounds), Poppins for
headings/display text and Inter for body text, Lucide-style outline icons via
a centralized `View::icon()` registry (`app/Core/View.php`), and a light/dark
theme system (`design-tokens.css`) that respects OS preference with an
explicit in-app override. All tokens live in
`public/assets/css/design-tokens.css` — nothing else should hardcode a color.
A dashboard-only teal accent (`--teal-50`…`--teal-900`) overrides
`--color-tertiary`/`--color-tertiary-soft` inside `.dash-shell` (chart
lines, secondary highlights) per a later client reference image — scoped
so the public marketing pages keep the original two-color Blue/Saffron
brand untouched. Dashboard page headers (`.dash-shell .services-hero__title`,
`.trainer-hero__title`) are also deliberately smaller than their public-page
counterparts, and the SVG analytics charts (`assets/js/modules/charts.js`)
use a flatter 320×110 aspect ratio (was 320×160) so they don't balloon in
height on wide grid cells. The landing page's hero headline uses a third
display face, Playfair Display (`--font-hero`, loaded only via `bare.php`'s
Google Fonts link — dashboard pages never load it since nothing there uses
it). `--font-sans-intl` names Noto Sans Tamil/Malayalam/Devanagari as
fallbacks for future non-Latin content, but deliberately isn't loaded via a
`<link>` yet since nothing on the site renders those scripts today —
eagerly downloading unused font weights would hurt load performance for
zero visible benefit. The landing page's hero also uses the `--teal-*`
raw scale (tagline color, "How It Works" numbered circles, one of the
decorative hero glow orbs) as a secondary accent alongside Blue/Saffron —
unlike the dashboard's teal (a semantic `--color-tertiary` override), this
is applied directly via the raw tokens so it doesn't disturb the shared
token used elsewhere. **Dark-theme hero glow fix**: the hero's blurred
background orbs originally used `--color-primary`, which flips to a *light*
blue in dark mode for text-contrast reasons — reused as a large blurred
glow, that same light token washed the whole hero out into a hazy
light-blue haze instead of a deep navy. Fixed with dark-theme-only orb
color/opacity overrides (darker raw scale values, lower opacity), following
the same 3-tier override pattern (`@media prefers-color-scheme` →
`[data-theme="dark"]` → `[data-theme="light"]` always wins) the rest of
`design-tokens.css` already uses.

**Landing page** (`app/Views/landing.php`) is a single long-scroll page with
real DB-backed sections (packages, instructors, programs, live stats via
`PageController::splash()`) interleaved with CMS-editable copy
(`page_content` table, admin-editable at `/admin/content`, code-level
defaults in `PageContent::default*()` as fallback) and a few static
marketing sections (reviews, blog, transformation stories) matching the
same hardcoded-array pattern. Section order top to bottom: Hero → Trusted
By (real stat counters) → community quote banner → About → Why Choose →
Benefits → Programs → Meet Instructors → Gallery → Transformation Stories →
How It Works → Packages & Pricing → Testimonials → Reviews → FAQ →
Download App (honest "Coming Soon" — no real app exists, so the store
badges are deliberately non-interactive rather than linking nowhere) →
Blog → Newsletter → Contact → Footer. "Packages" and "Pricing" intentionally
share one section (real per-session package data) rather than duplicating
it, since the business model has no separate subscription-tier concept. The
hero headline highlights one word (`PageContent::defaultHero()`'s
`titleHighlight` field) in teal via a small three-part split-and-escape in
the view — safe because the highlighted word is matched against trusted,
code-defined default copy, not arbitrary user input. The hero stats row and
`Why Vipasa`'s four value props are deliberately kept to real, honestly-earned
numbers (instructor count, program count, live rating) — no fabricated
"70+ classes/day" or "98% happy members" style vanity metrics, even where a
reference design mockup showed them, matching the existing "real numbers,
honestly earned" principle already used in `PageController::splash()`.

**Error pages & global exception handling**: `app/bootstrap.php` registers a
`set_exception_handler` that logs any uncaught exception to
`storage/logs/php-error.log`, returns HTTP 500, and renders a branded
`errors/500` view (raw message/trace shown only when `APP_DEBUG=true`) —
previously an uncaught exception fell through to a raw PHP fatal-error page.
`errors/404.php` and `errors/500.php` share a `public/assets/css/pages/errors.css`
glass-card component (`.error-page__content`) for a consistent premium look
on both.

**Legal pages** (`app/Views/legal/{privacy,terms,refunds}.php`, routed at
`/privacy-policy`, `/terms-of-service`, `/refund-policy` via
`PageController@privacy/terms/refunds`) are placeholder-content pages (a
sticky in-page "On this page" table of contents + prose sections,
`public/assets/css/pages/legal.css`) linked from a new "Support" column in
the landing page footer — `.site-footer__grid` was widened from 4 to 5
columns to fit it.

**Admin & Trainer portal CSS**: `public/assets/css/pages/admin.css` (new)
gives Admin Settings a tabbed layout (reusing the existing `.tab-nav`/
`.tab-panel`/`initTabs()` pattern first built for the Admin Health Record
Center) instead of one long scroll of six stacked forms, and gives Reports
glass `.report-row` cards with a leading icon per report type.
`public/assets/css/pages/trainer.css`'s shared components
(`.trainer-hero`, `.trainer-stat`, `.trainer-schedule-row`) were upgraded
from solid `--surface-card` panels to the glass treatment
(`--surface-glass` + blur + hover-lift) used elsewhere — since every trainer
page (dashboard, bookings, calendar, earnings, profile, reviews,
notifications) already shares these classes, this upgraded all of them in
one pass. New `.trainer-list-card`/`.trainer-empty` components replace
plain "No reviews/notifications yet" text with an icon + message empty
state. A parallel generic `.empty-state` component was added to
`components.css` and applied to the client dashboard's payments, invoices,
favorites, and notifications pages (also switched from plain `.card` to
`.card` + `.card-glass`). The booking flow's `.booking-summary` panel
(confirm/success/failed pages) and the schedule page's package-info card
were similarly switched to the glass treatment — a purely cosmetic
background/border/shadow change, deliberately leaving the interactive
time-slot picker's states (`.time-slot.is-selected`/`.is-unavailable`)
untouched since those carry booking-flow meaning, not just styling.

**Theme toggle removed — the app is single-theme, no user-facing switch.**
Every layout (`bare.php`/`main.php`/`dashboard.php`) hardcodes a
`data-theme` attribute on `<html>` instead of reading `localStorage`/OS
preference, so the site always renders the same way regardless of a
visitor's system dark-mode setting. The toggle button markup,
`.theme-toggle` CSS, and the `theme.js` module were removed entirely (not
just hidden) since they're confirmed unused.

This was **light** for a stretch of this project's history, then switched
to **`data-theme="dark"`** (the current, permanent value) once the client
settled on the dark-navy direction shown in their reference mockup as the
final site-wide look — not just the landing page hero, every page. Swept
every major page/portal after the switch (services, instructors, gallery,
login/signup, legal pages, 404) to confirm the existing dark-theme token
overrides (already built out from when dark mode was just one of two
user-selectable options) held up correctly as the *only* theme, not just
as a toggle-able alternative — no rendering gaps found. The light-theme
token values remain in `design-tokens.css` as inert dead code (same
reasoning as before: safer than a multi-file stripping pass, and harmless
since nothing sets `data-theme="light"` anymore).

**Image alignment audit**: several real photos had their subject's face
cropped out by a plain `object-fit: cover` center-crop when the source
photo's aspect ratio didn't match its display box (the About section photo,
2 of 3 Transformation Story cards, the landing gallery preview's first
tile, and one Services package card all had this bug — fixed via per-image
`object-position` overrides, keyed alongside the image path rather than as
a blanket CSS rule, since a single global crop position doesn't fit every
photo's composition — confirmed by one blanket fix accidentally breaking a
different card's crop mid-session before being corrected).

**Design system component pass**: `.stat-card`/`.stat-card-row` (a KPI/metric
tile) was promoted from `admin.css` into `components.css` as a shared
cross-portal component and applied to the client dashboard's and admin
dashboard's stat rows (previously ad-hoc inline-styled `.card` divs, each
portal styled slightly differently). New genuinely-missing components added
to `components.css`: `.tooltip` (CSS-only, `[data-tooltip]` attribute,
applied to the gallery's grid/masonry view-toggle buttons), `.breadcrumb`
(applied to the instructor detail page), `.pagination`, `.drawer`, and a
generalized `.accordion-item` (the landing page's FAQ already has its own
working accordion via `.faq-item` — this is the same pattern generalized
for reuse elsewhere). `.avatar`/`.avatar-group` and skeleton loaders
(`.skeleton`/`.skeleton-text`/etc.) already existed from earlier work and
were *not* rebuilt — an initial duplicate pass was caught and reverted
before being committed to this file. `.pagination` has no real page wired
to it yet: every list in the app (10 clients, a handful of bookings/
packages) is small enough that paginating it would be artificial UI
complexity for a boutique-studio-sized dataset, not a real need — it's
built and ready for whenever data volume actually grows.

**Hero photo layout** (went through several iterations — this is the
current, settled state): `.hero__bg-photo` (`landing.css`) is a background
layer sitting between the decorative grain texture (`.hero__bg`, z-index 0)
and the hero copy (`.hero__content`, z-index 2), right-anchored at 56%
width. The photo is `meditation-portrait.jpg` — the plain, un-cut photo
(client-supplied, full room/wall/mat visible, no background removal)
with a CSS `mask-image` linear gradient fading its left edge to
transparent, so the hero's own dark gradient shows through at the seam
instead of a hard photo edge. An earlier version used a client-supplied
alpha-channel cutout (`meditation-cutout.png`, subject only) with a
blurred teal `::before` glow behind it instead of a mask; that was
swapped back to a plain full photo + edge fade per client direction.
`.hero__bg-photo > img` still uses a direct-child combinator deliberately —
a descendant selector also caught the small instructor avatar `<img>` tags
nested inside the trust badge, which must never pick up this mask.
The hero background itself is a flat `linear-gradient(180deg, #071524 0%,
#081827 25%, #091d2d 55%, #081827 100%)` on `.hero` — replaced the earlier
four animated color orbs entirely (removed ~100 lines of orb CSS/keyframes)
after client feedback that the orbs plus a separately-backgrounded nav
above the hero read as "two separated sections, not one hero." The nav
itself moved from a sticky sibling `<header>` before `<section class="hero">`
into an absolutely-positioned child *inside* the hero (`.nav-glass--hero-overlay`,
scoped to the landing page only via that modifier class — every other
page's nav is untouched, still the normal sticky `.nav-glass`), floating
at `top: 24px` over the hero's own single background instead of sitting
in front of a second one.
`.hero__copy`'s `max-width: min(700px, calc(48vw - 108px))` is exact
box-model math, not a rough guess — an earlier `min(700px, 45vw)` version
still let the headline run under the photo card at 1130-1920px viewports,
because it ignored `.hero__content`'s own 64px `padding-inline` and the
photo's inset. The `calc()` bakes in both those fixed offsets plus a
20px safety margin.
**Trust badge position bug**: the "Certified & verified instructors"
badge sat at `top: var(--space-8)` (32px) from the hero's top edge,
which is well clear of the nav *in normal document flow* — but
`.nav-glass` is `position: sticky` with `z-index: 300` and a translucent
glass background. Scroll even a few px and the stuck nav visually
overlaps that badge, showing it faded through the nav's own translucency —
looked exactly like a CSS opacity/mask bug (and was initially misdiagnosed
as one) but was actually a z-index/scroll-geometry issue. Fixed by moving
the badge to `top: 130px`, clear of the nav's full height regardless of
scroll position. The badge's background was separately hardened from the
shared `--surface-glass-strong` token (translucent white, meant for
solid-color page backgrounds) to opaque `--surface-bg`, since this badge
specifically floats over a photo whose tone varies.

**Cache-busting**: `View::asset($publicPath)` (`app/Core/View.php`)
appends `?v=<filemtime>` to any `/public` asset path; all three layouts'
CSS `<link>` and JS `<script src>` tags route through it. Before this,
every asset URL was a static path with no versioning, so browsers could
(and did) keep serving a stale cached stylesheet after an edit — a real
source of "my change isn't showing up" confusion during this session.
A plain reload now always fetches the current file; no hard-refresh needed.

**Nav bar and hero sizing**: `.nav-glass`'s `max-width` and padding, and
the hero's `min-height`/internal spacing, both went through an
increase-then-decrease cycle in this project's history — sized up for a
"more premium/spacious" pass, then dialed back down (current values:
nav `max-width: min(88vw, 1280px)`, logo `36px`; hero `min-height: 82vh`)
once the larger version pushed the hero's stats row below the fold on
common laptop viewport heights. If touching either again, check that the
full hero (headline through the stats row) still fits in a ~950-1000px-tall
viewport without scrolling — that was the concrete complaint that drove
the decrease.

**Dark theme color palette — exact client hex values.** The dark theme
(`:root[data-theme="dark"]` and its `@media (prefers-color-scheme: dark)`
mirror in `design-tokens.css`) was already close to this palette from
earlier work, then updated to pixel-exact client-supplied hex codes: a
5-step surface elevation scale (`--surface-bg: #071523` → `--surface-bg-alt:
#081827` → `--surface-card: #10263a` → `--surface-elevated: #132c44` →
new `--surface-hover: #193a55`, darkest to lightest), `--text-primary:
#ffffff` / `--text-secondary: #bfc8d2`, `--border-subtle: rgba(255,255,255,.06)`,
and `--color-accent`/`--color-success` switched from referencing the raw
`--saffron-500`/`--color-success-500` scale variables to direct hex
(`#e98d2c`/`#27ae60`) so this pass is exact-to-spec rather than
"close enough via the existing scale." `--color-primary` (a muted
steel-blue, `--vipasa-blue-300`, used for text/icon accents and the
Log In button's gradient) was deliberately left alone — it's the
brand's actual primary-vs-accent distinction (blue for standard actions,
orange reserved for high-emphasis CTAs) from early in the project, not
a "bright blue" in the Bootstrap/cyan sense the client's brief warned
against.

**Container width — 1200px kept, not widened.** A later brief asked for
"reduce width... 1280px/1360px," but `.container`'s existing `max-width:
1200px` was already narrower than those figures — widening it would have
been the opposite of the stated goal. Resolved by leaving `max-width` at
1200px and increasing desktop side padding instead (`padding-inline:
var(--space-12)` at ≥1024px, up from `--space-10`), which satisfies the
other stated goal ("larger side margins, more breathing space") without
contradicting "reduce width." `.container-wide` (1440px) exists as a
token but isn't used anywhere in the actual views, so it wasn't the
source of any "feels too wide" complaint either.

## Is it running right now?

If you're picking this up in the same session it was built in, it's likely
already live at **http://localhost:8000** — check before starting a second
copy. Otherwise, follow Setup below.

## Setup (first time only)

1. **Configure environment**
   ```
   copy .env.example .env
   ```
   The defaults target the portable PHP/MariaDB described below
   (`127.0.0.1:3307`, user `root`, no password). Change `DB_*` if you're
   pointing at a different MySQL instance.

2. **Create the database**
   ```
   D:\xampp\mysql\bin\mysql.exe -h 127.0.0.1 -P 3307 -u root < database/schema.sql
   D:\xampp\mysql\bin\mysql.exe -h 127.0.0.1 -P 3307 -u root < database/seed.sql
   ```
   (`schema.sql` and `seed.sql` are already fully up to date — you don't need
   the files under `database/migrations/`, those were one-time updates applied
   to this machine's dev database as the schema evolved.)

3. **Install PHP dependencies** — `vendor/` is committed to this repo (see
   "Composer dependencies" below), so this step is normally a no-op. Only run
   it if `vendor/` is missing (e.g. you cloned without it):
   ```
   php composer.phar install
   ```
   (or plain `composer install` if you have Composer on your `PATH`.)

## Running the app

Two things need to be running: MariaDB, then the PHP server. Both commands
below use **fully-qualified paths on purpose** — copy-paste them exactly as
written and they work from *any* directory (Desktop, `D:\`, wherever your
terminal happens to be), with no `cd` required first. Do not shorten them to
relative paths (e.g. `xampp\mysql\bin\mysqld.exe` or `-t public`) — that only
works if you happen to already be in the one specific folder each path is
relative to, which is what caused the errors in this build's own dev
sessions.
   
**1. Check first — it's very likely already running.** Before starting
anything, run:
```
tasklist /FI "IMAGENAME eq mysqld.exe"
tasklist /FI "IMAGENAME eq php.exe"
```
If both show a process, skip straight to step 3 (visit the site) — starting
a second copy of either will just fail with a "port already in use"
/ "module not found" style error and do nothing (harmless, but skip it).

**2. If they're not running, start MariaDB, then the PHP server** (two
separate terminal windows — each one blocks/keeps running, it's not done
when the prompt looks idle, that's normal):
```
D:\xampp\mysql\bin\mysqld.exe --defaults-file=D:\xampp\mysql\data\my.ini
```
```
D:\xampp\php\php.exe -S localhost:8000 -t D:\YOGA\public
```

**3. Visit** http://localhost:8000

To stop: close both terminal windows, or find and kill the processes
(`taskkill /F /PID <pid>` using the PIDs from `tasklist`).

## This machine's environment (context for why it's not a standard XAMPP setup)

This machine had no PHP/MySQL installed, C: had 0 bytes free, and the official
XAMPP installer requires a UAC prompt that couldn't be approved
non-interactively. A portable (zip, no-installer) PHP and MariaDB were set up
directly on the D: drive instead:

- PHP 8.3.32 (NTS) at `D:\xampp\php\php.exe`
- MariaDB 11.4.5 at `D:\xampp\mysql\` (data dir: `D:\xampp\mysql\data`)
- MariaDB runs on **port 3307**, not the default 3306 — a pre-existing MySQL
  8.0 Windows service on this machine already owns 3306 and was left untouched
- `php.ini` has `pdo_mysql`, `mysqli`, `mbstring`, `openssl`, `curl`,
  `fileinfo`, `zip`, and `gd` enabled (`zip`/`gd` were added for the PDF/Excel
  report export — see below)

If you deploy this elsewhere with a normal XAMPP/WAMP/Laragon/hosting install,
none of the above matters — just point `.env` at whatever MySQL instance you
have (default port 3306 is fine), make sure the `zip` PHP extension is enabled
(required by the Excel export), and serve `public/` through Apache or PHP's
built-in server as usual.

## Composer dependencies

Two libraries are used, both purely for the admin Reports export (PDF via
Dompdf, `.xlsx` via PhpSpreadsheet) — everything else in the app is still
hand-rolled PHP with zero dependencies. `composer.json`, `composer.lock`, and
the `vendor/` directory are all committed to this repo, so **no `composer
install` is needed to deploy** — upload/copy the project as-is (including
`vendor/`) to any PHP host (Hostinger, cPanel, VPS) and it works immediately,
even on hosts without shell/SSH access to run Composer. Only re-run
`composer install` yourself if you add/change a dependency.

## Demo accounts

| Role | Email | Password |
|---|---|---|
| Admin | `info@vipasayoga.com` | `S@njusai1010` |
| Instructor | `ananya.iyer@vipasa.demo` | `Instructor@123` |
| Instructor | `rahul.menon@vipasa.demo` | `Instructor@123` |
| Instructor | `sarah.thomas@vipasa.demo` | `Instructor@123` |
| Client (test account, created during dev) | `lakshmananselvaraj@gmail.com` | `TestPass123!` |

There's no seeded "real" client account beyond the test one above. **New
client signup is Google-only** (`/signup` just shows a "Continue with Google"
button — no email/password form) — see "Progressive Profiling onboarding"
below. The existing password-based client test account above still works
via `/login`'s email/password form; that path isn't removed, just no longer
offered to *new* clients. Admin/Instructor logins are untouched either way —
they still use email/password, and always will (there's no self-registration
for those roles). When adding an instructor via `/admin/instructors`, the
admin **sets their password directly** in the Add Instructor modal (password +
confirmation, min 8 chars): the trainer then signs in at `/login` with that
email/password and `Auth::redirectHome()` drops them straight on
`/trainer/dashboard`. The account-ready email deliberately carries only the
login URL, never the password — the admin hands that over directly. Leaving
both password fields blank keeps the original behaviour instead: a null
`password_hash` plus an emailed "set your password" link (the same mechanism as
`/forgot-password`), useful where SMTP is configured and the trainer should
choose their own. Both fields are create-only — on edit they're hidden *and*
disabled, and the server's validation rules drop `email`/`password` entirely,
so an edit can never reset a trainer's own password. Note that the progressive
profiling questionnaires are client-only and can never reach a trainer: every
`/onboarding/*` route, `/dashboard` and `/booking/schedule` is gated by
`role:client`, so an instructor session gets a 403 rather than an assessment.

**Account status / hiding test data (never deletes anything)**: `users.status`
and `instructors.status` both support a `test` value (migration `011`) that
hides an account from public pages (`/instructors`, `/instructors/{id}`) and
from the *default* admin list views (`/admin/customers`, `/admin/instructors`)
— without ever deleting the row. Admins can still see everything via the
"Test accounts" / "All" filter chips on those pages. The client test account
above is flagged `test` for exactly this reason — it still logs in and works
normally, it's just hidden from the founder-facing customer list and from the
admin dashboard's "Total clients" / "New clients" figures. Gallery photos have
the same non-destructive pattern via `gallery_images.is_active` (migration
`012`) — admin can hide a photo from the public `/gallery` page without
deleting it, via a checkbox on `/admin/gallery`.

`APP_DEBUG=true` (the `.env.example` default) logs outbound "emails" (signup
verification link, welcome email, password reset, instructor invite, booking
confirmation/receipt/invoice/cancellation/reminder, contact form notices) to
`storage/logs/mail.log` instead of actually sending them, since no real SMTP
provider is configured — open that file to grab a link while testing locally.

## What's wired up vs. what needs real credentials

Everything below is fully built and tested, but some integrations are stubbed
against placeholder `.env` values until you supply real credentials:

- **Payments (Razorpay or Stripe, test or live)** — booking creation,
  checkout, signature/status verification, and post-payment emails all work
  against whichever gateway `PAYMENT_GATEWAY` selects (`razorpay` by default,
  or `stripe`). Both are blank in `.env.example`, so completing a payment
  against the *real* gateway isn't possible until you add real (sandbox or
  live) keys — `RAZORPAY_KEY_ID`/`RAZORPAY_KEY_SECRET` or
  `STRIPE_PUBLIC_KEY`/`STRIPE_SECRET_KEY`. **Switching gateways, or switching
  test keys for live keys, is purely an `.env` change — no code changes are
  needed** (see `app/Core/Payments/` for the gateway abstraction). Stripe uses
  a hosted Stripe Checkout page (redirect-based), so no Stripe.js UI work is
  needed either. In the meantime, `app/Core/Payments/MockGateway.php`
  automatically stands in whenever `PAYMENT_MODE=test` and no real gateway
  keys are set, so "Proceed to Payment" completes the full booking flow
  end-to-end (no external calls) instead of being disabled — useful for
  demoing/testing before real credentials exist. It's never used once real
  keys are present, regardless of `PAYMENT_MODE`.
- **Google Sign-In** — the OAuth flow (redirect, callback, account
  linking/creation) is fully implemented; `GOOGLE_CLIENT_ID`/`GOOGLE_CLIENT_SECRET`
  are blank, so the button currently shows "Google sign-in isn't configured
  yet" rather than erroring. **This is now the only way for a new client to
  create an account** (see "Progressive Profiling onboarding" below), so
  until real credentials are added, nobody can sign up as a new client in
  this environment — existing password-based client accounts still log in
  fine via `/login`. To activate: create a Google Cloud OAuth Client ID (Web
  application), add `GOOGLE_REDIRECT_URI` as an authorized redirect URI
  there, and either add the credentials to `.env` or paste them into
  `/admin/settings` → Google Login (settings there take priority over
  `.env`, no redeploy needed). Note for the client: a freshly created OAuth
  consent screen starts in "Testing" mode and only allows pre-approved test
  emails to sign in — it needs to be submitted for verification/publishing in
  Google Cloud Console before real customers can use it. Any existing test
  account signs in seamlessly the first time it uses Google if the Gmail
  address matches — accounts are auto-linked by email.

### Progressive Profiling onboarding (retention-first client signup)

Client signup was redesigned around a "get value fast, ask for the rest
later" model instead of a long registration form:

1. **Google Sign-In only** — clicking "Continue with Google" on `/login` or
   `/signup` auto-creates a Client account (name/email/photo from Google,
   no password). `Api\AuthController::signup` (email/password client
   registration) has been removed entirely — Admin/Instructor password login
   is untouched.
2. **Stage 1 — `/onboarding/get-started`** (`OnboardingController::getStarted`) —
   a single focused screen (no sidebar/dashboard chrome) collecting only
   Mobile, Date of Birth (age is calculated automatically, never entered),
   Gender, Height, Weight, City, Client Category, and Emergency Contact.
   Saving redirects straight to `/dashboard` — nothing else is required
   before a client can look around, browse instructors, etc.
3. **`name`, `phone`, and `date_of_birth` are locked forever** once first
   saved — `Api\ProfileController::update()` silently ignores incoming
   changes to those three fields server-side, and both `get-started.php` and
   `profile-wizard.php` render them `disabled` once set.
4. **`/onboarding/profile`** ("Edit Profile", the pre-existing full profile
   page) is never a forced gate — a client can reach `/dashboard` directly
   after Stage 1 and come back to Edit Profile whenever they want to add
   bio/health notes/photo/etc.
5. **Profile completion widget** on `/dashboard` — `ClientProfile::completionPercent()`
   is a pure, always-recomputed (never cached) mapping from "highest stage
   reached" to the client's exact `20/40/65/80/100` milestones
   (`ClientProfile::stageReached()` reads `health_assessment.stage`, clamped
   1-5; stages 4-5 don't exist yet, so 100% isn't reachable until they ship).
   The first time a save crosses a milestone, the dashboard fires confetti
   (`assets/js/modules/confetti.js`) and records a `Notification` — gated on
   a per-session "last seen milestone" so it never refires on a page reload.
6. **Stage 2 — conversational Health & Safety questionnaire, gated before a
   client's first booking** (`/onboarding/health-assessment`,
   `Api\HealthAssessmentController`). Never shown on dashboard entry — only
   triggered when a client actually tries to book
   (`BookingController::schedulePage` redirects there and back, preserving
   the original instructor/package query string; `Api\BookingController::store`
   has the same guard server-side as defense in depth). Headed "Start Health
   Assessment", with a `Step X of N` counter, a segmented progress bar, the
   5–10 minute estimate, Privacy/Encryption badges, and a Save & Continue
   Later button that stores the current screen's partial answers and returns
   to the dashboard. One topic per screen — Medical Conditions → conditional
   per-condition follow-ups → Health Screening (surgery / medication /
   injuries / allergies / ever-advised-not-to-exercise, each with a
   reveal-on-"Yes" follow-up) → Pain Assessment (chip-based body-area list,
   not a literal SVG body map) → conditional per-area follow-ups (1–10
   severity, side, continuous vs occasional, walking pain, diagnosis,
   treatment) → Doctor Restrictions → a category-specific safety step. All six
   categories have their own question set (53 questions total: 8 child, 9
   teenager, 8 adult, 9 senior, 9 pregnant, 10 postpartum), declared once as
   `CATEGORY_QUESTIONS` in that view — that spec is the source of truth for the
   wording, field types (`yesno`/`choice`/`number`) and ordering, and the step
   renders and validates itself from it, so adding a question is a one-line
   change there. It is mirrored by `CATEGORY_SAFETY_FIELDS` in
   `Api\HealthAssessmentController`, which rejects any field not whitelisted for
   the client's own category (so one category can't post another's answers) —
   **the two must be kept in sync**: a field present in the view but missing
   from the whitelist fails the entire step save with a 422. Broad themes per
   category: `child` guardian/school/screen-time; `teenager` guardian consent,
   study load, exam pressure; `adult` working-day pattern, sitting hours,
   practice pace and session length; `senior_citizen` falls, mobility, floor
   transfer, balance, dizziness, sensory difficulty; `pregnant_woman` trimester,
   weeks, doctor approval, complications, symptoms; `postpartum_mother` delivery
   type/clearance, breastfeeding, pelvic floor, incision pain, sleep and support.
   All fields are required before Continue unlocks — the step is a pre-booking
   safety gate, so partial answers aren't accepted (add `optional: true` to a
   field in the spec if a question should ever stop blocking).
   Every step auto-saves immediately (`client_profiles.health_assessment`
   JSON, shallow-merged per topic — see `ClientProfile::mergeHealthAssessment`)
   and records `currentStep`, so closing the browser mid-flow and coming back
   resumes at the exact right screen instead of restarting. The last step
   swaps the wizard for a completion screen (100%, a display-only wellness
   score and recommended level, both explicitly labelled as not a medical
   assessment) whose Continue button carries on to the pending booking.
   The `healthScreening` answers reach the Trainer's Medical Alerts card via
   `ClientProfile::safetySummary()` — allergies and "advised not to exercise"
   raise an alert on both the session and student-detail views.
7. **Stage 3 — conversational lifestyle questionnaire, surfaced once a
   client has completed their first session** (`/onboarding/wellness-profile`,
   `OnboardingController::wellnessProfile`). Unlike Stage 2, this is never a
   forced gate — it only appears as a dashboard card CTA ("Continue Your
   Wellness Profile") once `completedCount >= 1` and stage is still 2; a
   client can ignore it and keep using the app. Steps: Lifestyle (occupation/
   smoking/alcohol) → Sleep → Exercise → Stress → Yoga Experience → Yoga
   Goals (multi-select chips) → conditional goal follow-ups (Weight Loss →
   primary focus; Meditation/Stress Relief → preferred style) → Support
   System → Recovery Goals (shown only for `postpartum_mother`/
   `senior_citizen`, or if Stage 2's pain assessment had any non-"no pain"
   entry). Same auto-save/resume engine as Stage 2, reused via
   `Api\HealthAssessmentController::saveStep`'s shared step whitelist and a
   generalized `stage` parameter (`ClientProfile::stageReached()` /
   `saveStep` no longer hardcode "2" — stage only ever advances, never
   regresses on a re-save).
8. **Stage 4 — advanced health check-in, gated before a client's second
   booking** (`/onboarding/advanced-health`, `OnboardingController::advancedHealth`).
   Same "only interrupt when they act" pattern as Stage 2 —
   `BookingController::schedulePage`/`Api\BookingController::store` redirect
   there (and back) once a client already has at least one booking on
   record and hasn't reached stage 4 yet. Steps: Health Update (only shown if
   Stage 2 recorded a real medical condition) → Medication → Surgeries →
   Pain Update (change since last check-in + optional new pain areas) →
   Fall Risk → Lifestyle Preferences (class time/type) → a read-only
   Recommendations screen — a small rules-based summary from the answers
   just given (e.g. pain "worse" → suggests a gentler pace; "private" class
   type → flags private-session availability), not a forced question.
9. **Stage 5 — final details, review, and mandatory legal consent, completing
   the profile to 100%** (`/onboarding/complete-profile`,
   `OnboardingController::completeProfile`). Never a gate — surfaced only via
   a "Complete Your Profile" dashboard card once Stage 4 is done. Steps:
   Fitness Background → Food Preference → Communication Preference →
   Language Preference → Long-Term Goals → a read-only Review screen
   summarizing key answers → Consent (the three canonical statements as
   required checkboxes — "Finish" stays disabled until all three are
   checked). Consent is recorded permanently in a new append-only `consents`
   table (`Api\ConsentController`, `App\Models\Consent`) — `consent_version`,
   IP, user agent, timezone, timestamp — with `booking_id` left `NULL` for
   this one-time general consent (a future per-booking "before Join Session"
   consent reuses the same table with `booking_id` set, instead of needing a
   second table). Note: Occupation is deliberately not re-asked here — it
   was already collected in Stage 3's Lifestyle step.
10. **Admin Health Record Center, versioned audit trail, curated Trainer view,
    and per-booking Join Session consent — all built.** See the "Health
    Records, Audit Trail & Consent" section below.

### Health Records, Audit Trail & Consent

- **Admin Health Record Center** (`/admin/customers/{id}/health-record`,
  `Admin\CustomerController::healthRecord`) — every field a client's
  progressive profile has collected, in one tabbed page: Overview, Basic
  Information, Emergency Contact, Client Category, Health Assessment,
  Medical History, Pain Assessment, Lifestyle, Yoga Goals, Current
  Medication, Restrictions, Booking History, Profile Completion, Consents,
  Audit History, Version History, Timeline. A "Medical Alerts" banner at
  the top surfaces anything safety-relevant (conditions, pain, restrictions,
  medication) without needing to open a specific tab. Tabs use a small
  reusable component (`.tab-nav`/`.tab-panel` in `components.css`,
  `assets/js/modules/tabs.js`) — the first use of a tab pattern in this
  codebase, intended to be reused elsewhere.
- **Trainer visibility is curated, not just under-rendered** —
  `ClientProfile::safetySummary()` is the single source of truth for what a
  Trainer is allowed to see about a client (Medical Alerts, Pain Areas,
  Restrictions, Current Medication, Goals, Emergency Contact, Additional
  Notes, Profile Completion), used by both `Trainer\StudentController` and
  `Trainer\SessionController` so the two views can never drift apart on what's
  exposed. Neither controller passes the full `User`/`ClientProfile` row to
  its view anymore — only this curated subset, plus a compact "Medical
  Alerts" card now shown directly on the session page itself (not just a
  drill-down), so a trainer sees what they need before conducting the
  session without extra clicks.
- **Versioned profile-edit audit trail** — `profile_edit_history` (migration
  `016`, append-only, never overwritten) records one row per changed field:
  old value, new value, who changed it, a simple per-user incrementing
  `version` number, browser/device (parsed via the new dependency-free
  `App\Core\UserAgent` helper), timezone, timestamp, and an optional note.
  Wired into both `Api\ProfileController::update()` (Edit Profile saves) and
  `Api\HealthAssessmentController::saveStep()` (every conversational-step
  auto-save, logged as `health_assessment.<topic>`) — every profile update
  across the whole progressive-profiling system is versioned, not just the
  Edit Profile page.
- **Client Profile Timeline** — the Admin Health Record Center's Timeline tab
  renders `ActivityLog::forUser()` as a friendly icon+label narrative (Account
  Created, Booking Created, Payment Completed, Consent Accepted, Health
  Assessment Stage Completed, Session Completed, Review Submitted, etc.);
  the Audit History tab shows the same underlying log as a denser technical
  table. A few new log points were added to make this complete: distinct
  `account_created` (vs. every-login `login_google`), `health_assessment_stage_completed`,
  `consent_accepted`, and `session_completed` (logged against the *client's*
  user_id, not the trainer who triggered it, so it lands on the right
  person's timeline).
- **Mandatory legal consent before Join Session** — clicking "Join session"
  on `/dashboard/bookings` no longer links straight to the external Zoom/Meet
  URL; it goes to `/booking/{ref}/join` first (`BookingController::joinPage`),
  which never renders the real meeting link until a `consents` row exists for
  that exact booking. The three canonical statements are required checkboxes
  — the join button stays disabled until all three are checked, and consent
  reuses the same `Consent`/`Api\ConsentController` built for Stage 5, just
  with `booking_id` set this time instead of `NULL`.
- **Branding logo** — both theme variants are wired up and used consistently
  everywhere: `public/assets/img/brand/logo-dark.png` (glowing white-on-navy
  lockup, dark theme) and `logo-light.jpg` (navy-on-white lockup, light
  theme). The toggle is a shared `.brand-logo--dark`/`.brand-logo--light`
  utility (`components.css`) used by both the dashboard sidebar
  (`dash-sidebar__brand-logo`) and the public nav (`nav-glass__brand-logo`,
  `layouts/main.php` + `landing.php` + `gallery.php`). If you ever add a new
  place the logo needs to appear, reuse this same pair of `<img>` tags with
  both modifier classes — a previous version of this toggle used
  page-specific class names at equal CSS specificity to the sizing rule,
  which silently showed *both* logos stacked depending on which stylesheet
  loaded last; the current selectors (`img.brand-logo--dark/light`) are
  deliberately more specific to avoid that regardless of `<link>` order.

## Upcoming-session email reminders (scheduled task)

`bin/send-reminders.php` is a standalone CLI script — it emails a reminder for
every confirmed booking starting in the next 24 hours that hasn't already had
one sent (`bookings.reminder_sent_at` tracks this, so it's always safe to run
more often than needed; nothing is ever double-sent). It isn't wired up to run
automatically — schedule it yourself:

- **Windows (this dev machine)**, e.g. every 15 minutes:
  ```
  schtasks /create /tn "VipasaReminders" /tr "D:\xampp\php\php.exe D:\YOGA\bin\send-reminders.php" /sc minute /mo 15
  ```
- **Linux hosting (cPanel "Cron Jobs" or a crontab)**, e.g. every 15 minutes:
  ```
  */15 * * * * /usr/bin/php /home/youruser/vipasa/bin/send-reminders.php
  ```

Run it manually any time with `D:\xampp\php\php.exe bin\send-reminders.php` —
it prints a one-line summary (`Reminders sent: N (of M due)`).

## Site map

- `/` — public landing page (guests) / splash → dashboard redirect (logged in)
- `/signup` — Google Sign-In only for new clients (no password form);
  `/login`, `/forgot-password`, `/reset-password` — still email/password,
  used by Admin/Instructor and any pre-existing password-based client
  account. Login/signup/Google OAuth all support a `?redirect=` return-to
  param — if an anonymous visitor gets bounced here mid-booking (see below),
  they land back on the exact page/step they came from after authenticating,
  not a generic dashboard.
- `/onboarding/get-started` — Stage-1 minimal intake shown once, right after
  a first-time client's Google sign-in; saving unlocks `/dashboard`
  immediately. `/onboarding/profile` — the full "Edit Profile" page, never a
  forced gate, reachable any time from the dashboard.
- `/onboarding/health-assessment` — Stage-2 "Start Health Assessment"
  questionnaire; only reached by trying to book a first session (never
  forced on dashboard entry), returns to the exact booking URL afterward,
  and can be paused mid-flow via Save & Continue Later.
- `/onboarding/wellness-profile` — Stage-3 conversational lifestyle
  questionnaire; only reached via a dashboard card once a client has
  completed their first session, never a forced gate.
- `/onboarding/advanced-health` — Stage-4 advanced health check-in; gated
  before a second booking (same "block the action, not the dashboard"
  pattern as Stage 2), returns to the exact booking URL afterward.
- `/onboarding/complete-profile` — Stage-5 final details, review, and
  mandatory legal consent; reached via a dashboard card once Stage 4 is
  done, completes the profile to 100%.
- `/gallery` — public photo gallery (masonry/grid toggle, category filter,
  lightbox), `/privacy-policy`, `/terms` — all public, no login required
- `/services`, `/instructors`, `/instructors/{id}` — **public, no login
  required** (a prospective customer can browse the full catalog and every
  instructor's profile before creating an account — only the actual booking
  step requires auth). `AuthMiddleware` captures the originally-requested URL
  when it bounces an anonymous visitor to `/login`, so clicking "Book Now"
  while logged out redirects to login and back to that exact booking step
  automatically.
- `/booking/schedule` → `/booking/confirm/{ref}` → Razorpay checkout modal or
  Stripe Checkout redirect (whichever `PAYMENT_GATEWAY` selects, or the
  built-in `MockGateway` if no real keys are set — see below) →
  `/booking/{ref}/success` or `/booking/{ref}/failed` — the booking + payment
  flow (requires login)
- `/booking/{ref}/invoice` — printable invoice
- `/booking/{ref}/join` — mandatory legal consent gate before revealing the
  real meeting link for a confirmed booking; never renders the external
  URL until a `consents` row exists for that exact booking
- `/dashboard/bookings` — client's own bookings, split into Upcoming and
  Completed & Past, with a Cancel action on any still-future booking
- `/dashboard/payments` — client's own payment history across all bookings
- `/dashboard/invoices` — client's own list of invoices (confirmed/completed
  bookings only), linking to the printable invoice page
- `/admin` — analytics dashboard (revenue/bookings/user-growth charts,
  today's schedule, upcoming/cancelled sessions)
- `/admin/bookings` — manage bookings, attach Google Meet/Zoom links (the
  meeting-link/reschedule forms only render for bookings actually
  `awaiting_trainer_approval`/`confirmed` — not for pending-payment or
  cancelled ones, to keep the list uncluttered)
- `/admin/calendar` — cross-instructor month view of all bookings
- `/admin/packages` — create/edit/activate packages (only 3 — Single Session,
  Weekly, Monthly — are `is_featured` and show on the public Services page by
  default; add more here any time)
- `/admin/instructors` — create/edit/deactivate instructors (creates the
  paired user account and emails a password-set invite link); shows an
  Active/Inactive/Test badge per instructor
- `/admin/customers` — browse clients, drill into a customer's full booking +
  payment history; "Active customers" (default) / "Test accounts" / "All"
  filter chips — see account status system above
- `/admin/customers/{id}/health-record` — the full tabbed Health Record
  Center (Overview/Basic Info/Emergency Contact/Category/Health Assessment/
  Medical History/Pain Assessment/Lifestyle/Goals/Medication/Restrictions/
  Bookings/Completion/Consents/Audit History/Version History/Timeline)
- `/admin/payments` — all payment transactions, filterable by status/gateway
- `/admin/gallery` — upload/edit/reorder public gallery photos, with a
  "Visible on public gallery" checkbox per photo (hide without deleting)
- `/admin/settings` — edit site name, tax percent, default currency/timezone,
  payment/mail/Google credentials, and a reminder banner that the legal pages
  need professional review before launch (see below)
- `/admin/notifications` — send an in-app notification to any user; view sent
- `/admin/video-sessions` — placeholder for the inbuilt video classroom (a
  separate future build phase — sessions currently use the meeting-link field
  on `/admin/bookings`)
- `/admin/reports` — revenue/bookings/payments/instructor-performance reports,
  downloadable as CSV, real PDF, or real Excel (`.xlsx`), or viewed as a
  printable HTML page

Every `/admin/*` page shares one admin sub-navigation bar
(`app/Views/partials/admin-nav.php`) linking all of the above.

## Security

Bcrypt password hashing, 100% PDO prepared statements, CSRF tokens on every
state-changing request, output escaping via `View::e()`, role-based
middleware, secure/httponly/samesite session cookies with regeneration on
login, and a DB-backed rate limiter on login/signup/password-reset/contact
endpoints. The post-login `?redirect=` return-to feature validates the target
is a same-site relative path (`Auth::isSafeRedirectPath()`) before ever
redirecting to it, to prevent it being used as an open redirect. A global
exception handler (`bootstrap.php`) catches any uncaught error: with
`APP_DEBUG=true` it still shows the full trace for local development, but
with `APP_DEBUG=false` (production) it logs the full details server-side to
`storage/logs/php-error.log` and shows the branded `errors/500` page instead
of leaking a stack trace or a blank white screen to a real visitor.

## Before going live with real customers

This is a fully working, tested build — but not production-ready as-is:

1. Add real Razorpay or Stripe credentials and Google OAuth credentials (see
   above) — going from sandbox/test to live payments is purely swapping the
   `.env` key values (and optionally `PAYMENT_GATEWAY`), no code changes.
2. Deploy to real hosting with HTTPS (PHP's built-in dev server used here is
   explicitly not for production) and point a real SMTP provider at
   `MAIL_DRIVER`/`MAIL_HOST`/etc. instead of the local `log` driver.
3. Set `APP_ENV=production`, `APP_DEBUG=false`, a real random `APP_KEY`,
   `SESSION_SECURE_COOKIE=true`, and `PAYMENT_MODE=production`.
4. Schedule `bin/send-reminders.php` on the host via Task Scheduler or Cron
   (`D:\xampp\php\php.exe bin/send-reminders.php` — handles 24h & 1h session reminders).
5. Payment Webhooks (`/api/webhooks/razorpay` and `/api/webhooks/stripe`) are active and signature-verified.
6. Mailer (`app/Core/Mailer.php`) supports authenticated SMTP socket transport configured directly via Admin Settings.
7. **Get the legal pages reviewed by an actual lawyer.** `/privacy-policy` and
   `/terms` (`app/Views/legal/`) are real, substantive, platform-specific
   content — not lorem ipsum — but they are template documents, not legal
   advice. They still need a qualified legal professional to review and
   approve them, and to fill in specifics this build can't know on its own:
   the actual cancellation/refund window, and the governing-law jurisdiction.
   A reminder banner is already shown on `/admin/settings` so whoever owns
   the launch doesn't miss this.
8. Do a full visual pass across every page at every breakpoint (320–1920px)
   and a full WCAG AA accessibility audit. A great deal of this has already
   been done and verified in an actual browser this build cycle (the full
   booking→payment→dashboard journey, all three portals' key pages, mobile
   overflow, focus states, contrast on the new brand colors) — but it wasn't
   exhaustive across every single page/breakpoint combination, so budget time
   to finish that sweep before launch.
9. Consider whether the admin dashboard's revenue/booking charts should
   exclude bookings made by `test`-status accounts (currently they don't —
   only the client-count figures were fixed to match the customer list).

Field	Value
Email	ananya.iyer@vipasa.demo
Password	Instructor@123


Field	Value
Email	info@vipasayoga.com
Password	S@njusai1010

run command 
D:\xampp\php\php.exe -S localhost:8000 -t D:\YOGA\public
D:\xampp\mysql\bin\mysqld.exe --defaults-file=D:\xampp\mysql\data\my.ini