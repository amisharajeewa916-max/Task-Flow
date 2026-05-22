# TaskFlow Security Documentation

## Threats Identified

- Unauthorized access to task, project, and notification resources
- Broken authentication and session management
- Unvalidated file uploads or insecure file storage
- Cross-site scripting via user-submitted comments or task names
- SQL injection through dynamic database queries
- Insecure API token usage

## Implemented Mitigations

### Password Hashing
- All application user passwords are hashed with Laravel's built-in `Hash::make()`.
- Password hashing is enforced for registration, seeding, and API registration.

### CSRF Protection
- All Blade forms use `@csrf` where applicable.
- Jetstream automatically protects all HTTP forms and routes.

### SQL Injection Prevention
- The application uses Eloquent ORM and parameterized validation rules.
- No raw string interpolation is used for SQL commands in controllers.

### XSS Prevention
- Blade templates use `{{ }}` auto-escaping for user input.
- Inputs are validated and sanitized by Laravel validation rules.

### Input Validation
- Task, project, and team interactions use Laravel form requests or validator rules.
- API endpoints validate data and return consistent `422` responses for invalid input.

### Role-based Access Control
- `admin`, `manager`, and `user` roles are enforced using middleware and policies.
- `RoleMiddleware` blocks unauthorized API and web access.
- `TaskPolicy` and `ProjectPolicy` ensure users only view or modify permitted resources.

### Session Security
- Sessions use secure cookie flags via `SESSION_SECURE_COOKIE`.
- `HttpOnly` cookies are enabled in `config/session.php`.
- Session inactivity timeout is configured in `.env` via `SESSION_LIFETIME`.

### File Upload Security
- Attachments are validated for file type and size in `TaskController@uploadAttachment`.
- Allowed MIME types are restricted to `jpg,jpeg,png,pdf,doc,docx,zip`.
- Files are stored under `storage/app/private/attachments` to avoid public exposure.

### API Token Security
- API authentication uses Laravel Sanctum.
- Tokens are revoked on logout via `ApiAuthController@logout`.
- API routes are protected with `auth:sanctum` and role middleware for manager/admin resources.

## OWASP Top 10 Coverage

- **A1: Broken Access Control** — enforced by policies, role middleware, and route guards.
- **A2: Cryptographic Failures** — passwords are hashed securely; sessions can use HTTPS-only cookies.
- **A3: Injection** — prevented by using Eloquent and Laravel validation.
- **A4: Insecure Design** — secure defaults are applied, including authorization checks and validation.
- **A5: Security Misconfiguration** — `.env.example` includes secure defaults for production.
- **A6: Vulnerable and Outdated Components** — uses Laravel 12 and supported package versions.
- **A7: Identification and Authentication Failures** — Jetstream handles authentication with secure password reset.
- **A8: Software and Data Integrity Failures** — data is validated on all write operations.
- **A9: Security Logging and Monitoring** — Laravel logging is available via default channels.
- **A10: Server-Side Request Forgery (SSRF)** — external requests are not processed from user input.
