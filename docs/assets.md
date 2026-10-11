# Asset roles (images / logos / favicons)

Single source of truth for which image file plays which role and where it is
used. Read this before renaming, replacing, or re-wiring any image asset —
several files have hidden dual roles.

## Brand vs. marketing split

- **Root `assets/img/`** = brand/app assets (favicon, app-logo fallback). These
  are what a per-deployment override replaces.
- **`assets/img/mebeauty/`** = marketing/website assets only (wordmark, hero,
  social icons). Layouts must not reference this folder for the favicon or the
  app logo.

## Logo precedence

The app logo is resolved at runtime: `company_logo` (uploaded, base64 data URI)
wins; `assets/img/logo.png` is only the fallback when `company_logo` is empty.
The pattern is `setting('company_logo') ?: base_url('assets/img/logo.png')`.

## Files

| File | Role | Used in |
|---|---|---|
| `assets/img/favicon.ico` | Browser-tab favicon (16×16). The **only** favicon. | all layouts (`backend`, `account`, `message`, `booking`) + `installation` |
| `assets/img/logo.png` | App-logo **fallback** (square). Not the favicon. | `backend_header`, `booking_company_header`, `Email_messages`, `login`, `recovery`, `password_reset`, `logout`, `about` |
| `company_logo` (setting, base64) | Uploaded logo; overrides `logo.png`. | same surfaces as `logo.png` (via `setting('company_logo') ?: …`) |
| `assets/img/mebeauty/logo.png` | Marketing wordmark (rectangular); `logo.webp` is the WebP variant (lossless). | `site_navbar` (website navbar) |
| `assets/img/mebeauty/hero.jpg` | Booking intro hero image (3400×2240, JPEG quality 70, progressive, ≈ 290 KB fallback); `hero.webp` is the WebP variant (quality 85). | `site_intro` |
| `assets/img/mebeauty/instagram.png` `tiktok.png` `whatsapp.png` | Footer social icons. | `site_footer` |
| `assets/img/social-card.png` | Open-Graph preview (`og:image`). | `booking_layout` |
| `assets/img/logo-16x16.png` + `alextselegidis-logo-16x16.png` | License attribution logos. | `backend_footer` |

## Orphaned (no references)

These are no longer wired anywhere — candidates for deletion, not for reuse:

- `assets/img/mebeauty/icon-192.png` (was the booking 192×192 favicon)

## Gotchas

- Do **not** use `assets/img/logo.png` as a favicon. The favicon is
  `assets/img/favicon.ico` only; the old `sizes="192x192"` links were removed.
- The email logo is embedded from `company_logo` via
  `addStringEmbeddedImage(base64_decode($base64), …)`. Pass **decoded** bytes,
  not the base64 string — passing base64 with `'base64'` encoding double-encodes
  and produces a broken image.
