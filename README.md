# TaskFlow

TaskFlow is a Laravel 12 SaaS task management application built for COMP50016 – Server Side Programming II. It supports role-based access, team collaboration, task tracking, file attachments, comments, notifications, and secure API access.

## Features

- Laravel 12 with MySQL, Jetstream Livewire, Tailwind CSS, Sanctum, and Eloquent ORM
- Role-based permissions: `admin`, `manager`, and `user`
- Task CRUD with priority, deadlines, status, and project assignments
- Project management for managers and admins
- Team membership and team-based task visibility
- Livewire components for task filtering, creation, comments, and notifications
- REST API under `/api/v1/` with token-based authentication
- Seeders for users, teams, projects, tasks, comments, attachments, and notifications
- Security documentation in `SECURITY.md`

## Setup Instructions

1. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
2. Configure MySQL credentials in `.env`:
   ```text
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taskflow
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Install PHP dependencies:
   ```bash
   composer install
   ```
4. Install frontend dependencies:
   ```bash
   npm install
   ```
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```
6. Run migrations and seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```
7. Start the development server:
   ```bash
   npm run dev
   php artisan serve
   ```

## Seeded Accounts

- Admin: `admin@example.com` / `password`
- Manager One: `manager1@example.com` / `password`
- Manager Two: `manager2@example.com` / `password`
- Regular users: `user1@example.com` ... `user5@example.com` / `password`

## Web Routes

- `/` — Landing page
- `/dashboard` — User dashboard
- `/tasks` — My Tasks list
- `/tasks/create` — Create task
- `/tasks/{id}` — Task details
- `/tasks/{id}/edit` — Edit task
- `/team-tasks` — Team task list
- `/projects` — Projects list
- `/calendar` — Deadline calendar
- `/reports` — Progress reports
- `/settings` — User profile settings

## API Endpoints

### Authentication
- `POST /api/v1/register`
- `POST /api/v1/login`
- `POST /api/v1/logout`

### Tasks
- `GET /api/v1/tasks`
- `POST /api/v1/tasks`
- `GET /api/v1/tasks/{id}`
- `PUT /api/v1/tasks/{id}`
- `DELETE /api/v1/tasks/{id}`
- `PATCH /api/v1/tasks/{id}/complete`

### Projects (manager/admin only)
- `GET /api/v1/projects`
- `POST /api/v1/projects`
- `GET /api/v1/projects/{id}`
- `PUT /api/v1/projects/{id}`
- `DELETE /api/v1/projects/{id}`

## Testing

Run the test suite:
```bash
php artisan test
```

## Deliverables Included

- `SECURITY.md` — security documentation and OWASP coverage
- `database/taskflow_dump.sql` — MySQL schema dump
- `postman_collection.json` — Postman collection for API validation
- `tests/Feature/TaskflowBackendTest.php` — feature tests for backend flows

## Security Notes

- CSRF protection is enabled by default through Laravel and Jetstream
- Password hashing uses Laravel's secure hashing
- Role middleware and policies guard resources
- File uploads are validated and stored securely
- API authentication uses Laravel Sanctum

## Deployment Notes

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Enable HTTPS and `SESSION_SECURE_COOKIE=true`
- Configure production database credentials in `.env`
- Run migrations on deploy with `php artisan migrate --force`
