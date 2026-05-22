# TaskFlow Detailed Route Code Documentation

This document contains each line of code from the [web.php](file:///c:/xampp/htdocs/taskflow/routes/web.php) file separately and clearly explained.

---

## 1. Import Statements

These lines import the necessary classes, controllers, and facades required for routing.

### `Illuminate\Support\Facades\Route`
* **Code Line:**
  ```php
  use Illuminate\Support\Facades\Route;
  ```
* **Purpose:** Imports the Laravel Route Facade, which provides static interfaces to register routes in the application.

### `App\Http\Controllers\DashboardController`
* **Code Line:**
  ```php
  use App\Http\Controllers\DashboardController;
  ```
* **Purpose:** Imports the single-action controller for handling and displaying the dashboard dashboard.

### `App\Http\Controllers\TaskController`
* **Code Line:**
  ```php
  use App\Http\Controllers\TaskController;
  ```
* **Purpose:** Imports the controller that manages all task operations (listing, creating, editing, uploading attachments, etc.).

### `App\Http\Controllers\ProjectController`
* **Code Line:**
  ```php
  use App\Http\Controllers\ProjectController;
  ```
* **Purpose:** Imports the controller managing all project operations (CRUD).

### `App\Http\Controllers\CalendarController`
* **Code Line:**
  ```php
  use App\Http\Controllers\CalendarController;
  ```
* **Purpose:** Imports the single-action controller displaying the calendar and deadlines page.

### `App\Http\Controllers\ReportController`
* **Code Line:**
  ```php
  use App\Http\Controllers\ReportController;
  ```
* **Purpose:** Imports the single-action controller generated for managers and admins to view progress reports.

### `App\Http\Controllers\GoogleController`
* **Code Line:**
  ```php
  use App\Http\Controllers\GoogleController;
  ```
* **Purpose:** Imports the controller handling Google OAuth authentication.

---

## 2. Public / Guest Routes

These routes do not require the user to be logged in.

### Welcome Landing Page
* **Code Line:**
  ```php
  Route::get('/', function () {
      return view('welcome');
  });
  ```
* **Purpose:** Defines a root `GET` route that returns the landing page (`resources/views/welcome.blade.php`).

### Google OAuth Login Redirect
* **Code Line:**
  ```php
  Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
  ```
* **Purpose:** A `GET` route that triggers the Google login process. It routes to the `redirectToGoogle` action inside the `GoogleController` and is named `auth.google`.

### Google OAuth Callback Handler
* **Code Line:**
  ```php
  Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
  ```
* **Purpose:** A `GET` callback route where Google returns authentication data. It routes to the `handleGoogleCallback` action in `GoogleController` and is named `auth.google.callback`.

---

## 3. Authenticated Route Group

These routes are protected by a group middleware sequence ensuring the user is signed in and verified.

* **Code Line:**
  ```php
  Route::middleware([
      'auth:sanctum',
      config('jetstream.auth_session'),
      'verified',
  ])->group(function () {
      // Protected routes go here...
  });
  ```
* **Purpose:** Ensures that all nested routes require:
  1. `auth:sanctum`: Active user session or API token.
  2. `config('jetstream.auth_session')`: Authentication session configured by Laravel Jetstream.
  3. `verified`: Email verification completed.

### Dashboard
* **Code Line:**
  ```php
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  ```
* **Purpose:** A `GET` route displaying the main dashboard. It invokes the single-action `DashboardController` and is named `dashboard`.

### Team Collaborative Tasks List
* **Code Line:**
  ```php
  Route::get('/team-tasks', [TaskController::class, 'teamTasks'])->name('tasks.team');
  ```
* **Purpose:** A `GET` route running the `teamTasks` method of `TaskController` to list team tasks. Named `tasks.team`.

### Task Attachment Upload
* **Code Line:**
  ```php
  Route::post('/tasks/{id}/attachments', [TaskController::class, 'uploadAttachment'])->name('tasks.attachments.upload');
  ```
* **Purpose:** A `POST` route to upload files for a specific task using its ID. Invokes `uploadAttachment` in `TaskController`. Named `tasks.attachments.upload`.

### Task Attachment Download
* **Code Line:**
  ```php
  Route::get('/attachments/{id}', [TaskController::class, 'downloadAttachment'])->name('attachments.download');
  ```
* **Purpose:** A `GET` route allowing secure downloading of attachments using their ID. Invokes `downloadAttachment` in `TaskController`. Named `attachments.download`.

### Tasks CRUD Resource
* **Code Line:**
  ```php
  Route::resource('tasks', TaskController::class);
  ```
* **Purpose:** Registers all standard RESTful actions (Index, Create, Store, Show, Edit, Update, Destroy) for tasks automatically.

### Projects CRUD Resource
* **Code Line:**
  ```php
  Route::resource('projects', ProjectController::class);
  ```
* **Purpose:** Registers all standard RESTful actions (Index, Create, Store, Show, Edit, Update, Destroy) for projects automatically.

### Calendar View
* **Code Line:**
  ```php
  Route::get('/calendar', CalendarController::class)->name('calendar');
  ```
* **Purpose:** A `GET` route showing the deadline calendar. Invokes the single-action `CalendarController` and is named `calendar`.

### Progress & Performance Reports
* **Code Line:**
  ```php
  Route::get('/reports', ReportController::class)
      ->middleware('role:manager,admin')
      ->name('reports');
  ```
* **Purpose:** A `GET` route that generates performance reports. It incorporates additional role verification middleware requiring the user to have a role of `manager` or `admin`. Invokes `ReportController` and is named `reports`.
