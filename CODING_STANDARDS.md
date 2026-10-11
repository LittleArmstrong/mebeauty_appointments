# Coding Standards

Conventions enforced during code review. Tooling already catches syntax and the
email-logo smoke test catches the base64 gotcha; these are the judgement calls a
guardrail cannot enforce.

## Brand values come from settings

Original Easy!Appointments surfaces (backend header, emails, booking wizard
card, page titles, login/logout/recovery/about) resolve brand values through
settings and never hardcode a literal brand:

- Name → `setting('company_name', DEFAULT_COMPANY_NAME)`
- Logo → `setting('company_logo') ?: asset_url('assets/img/logo.png')`

No literal `"MeBeauty"`/`"Easy!Appointments"` strings or literal logo paths
belong in these views.

Exception: the site chrome of a theme (`application/views/themes/<theme>/site_navbar.php`, `site_intro`,
`site_footer`, `site_whatsapp`; ADR 0006) is a per-customer marketing asset and is intentionally not
settings-driven.

## Image/logo/favicon changes

Verify the target asset's role in `docs/assets.md` before replacing or re-wiring
it. In particular: `assets/img/logo.png` is a fallback, not the favicon; the
favicon is `assets/img/favicon.ico` only.
