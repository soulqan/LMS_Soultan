# Security Audit Notes - 2026-05-19

This document lists the main security risks I found in the current LMS codebase. I did not apply any fixes.

## High Priority

### 1) Hidden courses can still be opened directly by slug

- **Where:** [app/Http/Controllers/CourseController.php](/Users/mycomputer/lms-soultan/app/Http/Controllers/CourseController.php#L37) and [routes/web.php](/Users/mycomputer/lms-soultan/routes/web.php#L29)
- **What I found:** The public catalog only filters `is_available = true`, but the course detail route does not check `is_available` before rendering the page.
- **Why it matters:** If someone knows or guesses a slug, they may be able to view a course that was meant to stay hidden from the public catalog.
- **Impact:** Unpublished or disabled course content may be exposed.

## Medium Priority

### 2) Logout is allowed over `GET`

- **Where:** [routes/web.php](/Users/mycomputer/lms-soultan/routes/web.php#L23) and [app/Http/Controllers/Auth/AuthenticatedSessionController.php](/Users/mycomputer/lms-soultan/app/Http/Controllers/Auth/AuthenticatedSessionController.php#L37)
- **What I found:** The logout route accepts both `POST` and `GET`.
- **Why it matters:** A `GET /logout` endpoint can be triggered by a simple link or embedded request, which makes it easier to force a user to log out without their intent.
- **Impact:** Session disruption and logout CSRF-style behavior.

### 3) Login endpoint has no visible rate limiting

- **Where:** [routes/web.php](/Users/mycomputer/lms-soultan/routes/web.php#L13) and [app/Http/Controllers/Auth/AuthenticatedSessionController.php](/Users/mycomputer/lms-soultan/app/Http/Controllers/Auth/AuthenticatedSessionController.php#L19)
- **What I found:** The login form submits to a normal POST route without any obvious throttling middleware in the route definition.
- **Why it matters:** Repeated login attempts may be automated more easily.
- **Impact:** Brute-force password guessing becomes easier if additional protection is not enabled elsewhere.

### 4) Profile update does not require current password confirmation

- **Where:** [app/Http/Controllers/ProfileController.php](/Users/mycomputer/lms-soultan/app/Http/Controllers/ProfileController.php#L21)
- **What I found:** A logged-in user can change their name, email, and password without re-entering their current password.
- **Why it matters:** If a session is compromised, an attacker can immediately take over the account settings.
- **Impact:** Account takeover risk is higher than in a flow that asks for current-password confirmation.

## Lower Priority / Hardening

### 5) Registration is open without anti-automation protection

- **Where:** [routes/web.php](/Users/mycomputer/lms-soultan/routes/web.php#L13) and [app/Http/Controllers/Auth/RegisteredUserController.php](/Users/mycomputer/lms-soultan/app/Http/Controllers/Auth/RegisteredUserController.php#L19)
- **What I found:** Public registration is available and there is no visible captcha, email verification gate, or throttling in the route/controller layer.
- **Why it matters:** Automated sign-up spam is easier if no additional controls are added.
- **Impact:** Potential abuse of the registration endpoint.

## Notes

- The admin panel access control is role-based, which is a good improvement.
- Student accounts are hidden from the Filament user resource, which reduces accidental exposure inside admin UI.
- `email_verified_at` is used to track verified accounts, but the current profile flow still lets users update email without requiring a fresh verification step.

## Summary

The biggest practical concerns are:

1. direct access to hidden course slugs,
2. `GET /logout`,
3. no visible login throttling,
4. profile changes without current-password confirmation.

No fixes were applied in this audit note.

