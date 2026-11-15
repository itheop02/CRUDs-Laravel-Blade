# Laravel CRUD: Breeze Blade Version

This project is a simple Laravel CRUD for Tasks model built on top of Laravel Breeze starter kit Blade version.

![](https://laraveldaily.com/uploads/2024/12/crud-breeze-tasks.png)

---

## Installation

Follow these steps to set up the project locally:

1. Clone the repository:
   ```bash
   git clone https://github.com/LaravelDaily/CRUDs-Laravel-Blade.git project
   cd project
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run build
   ```

3. Copy the `.env` file and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Set up the database:
    - Update `.env` with your database credentials.
    - Run migrations and seed the database, repo includes fake tasks:
      ```bash
      php artisan migrate --seed
      ```

6. If you use Laravel Herd/Valet, access the application at `http://project.test`.

7. Log in with credentials: `test@example.com` and `password`.  

---

## Features to Pay Attention To

This project goes beyond the default Laravel Breeze setup with the following enhancements.

1. Return Types in the Controller: ex. `public function index(): View`
2. Utilizes Form Request classes for validation, with `$request->validated()` then used in the Controller
3. Includes Factory and Seeder for the `Task` Model
4. Modifies Laravel Breeze Blade component `x-input-label` to add a "required" asterisk parameter
5. Uses "flash" messages in the session to show the result after store/update/delete. Adds a custom Blade component `x-alert-message` for this.
6. Transform the links to look like buttons visually, with the same Tailwind styles as Laravel Breeze components. Adds a custom Blade component `x-link-button` for this.
7. Includes Pest test file `TasksCRUDTest` that has methods to test all Controller routes and also validation of each field.

![](https://laraveldaily.com/uploads/2024/12/crud-breeze-tasks-tests.png)

---

## Found a bug? Got a question/idea? 

Raise a GitHub issue or email `info@laraveldaily.com`. 

## PrivacyLib GDPR integration

Original GDPR framework: **[PrivacyLib-REGO](https://github.com/CS-UCY-SEIT-lab/PrivacyLib-REGO)**. The unchanged PHP router and eleven OPA/Rego modules are bundled in `integration/privacy-lib/`.

### Setup

Use the original PHP 8.2+ application requirements, PHP cURL, PDO SQLite (or the original MySQL driver), and [OPA 1.x](https://www.openpolicyagent.org/docs). Install Composer/frontend dependencies and configure the original app as above. Keep `.env`, application keys, databases, dependencies and build artifacts out of Git. Set `MAIL_MAILER=log` for local demonstrations.

Run `php artisan migrate` to add the separate `gdpr_consents` table. This migration does not alter existing users or tasks. Start OPA from this repository:

```sh
opa run --server --addr 127.0.0.1:8181 integration/privacy-lib/gdpr
```

`OPA_QUERY_URL` in `.env` defaults to `http://localhost:8181/v1/query`. Configure the synthetic controller, purposes, retention and trusted request checks in `integration/privacy-config.json` for your intended use.

Start the application:

```sh
php artisan serve --host=127.0.0.1 --port=8024
```

Sign in with the original demo account (`test@example.com` / `password`) or register using dummy details. No email verification is required by the original User model. Click **Privacy checks** in the top bar in the same signed-in browser tab/origin.

### Data scope and behavior

The routes use the original Laravel web authentication, session and CSRF middleware: `GET /gdpr`, `GET /gdpr/csrf`, `PATCH /gdpr/consent`, and eleven `POST /gdpr/{policy}` operations (consent, lawful, information, access, rectification, erasure, restriction, portability, objection, complaint, breach). This is 14 HTTP method/operation pairs. JSON requests require `Accept: application/json`, `Content-Type: application/json`, and `X-CSRF-TOKEN` for mutations. Laravel returns 419 for invalid CSRF and 422 for validation failures.

The signed-in account determines the subject; clients cannot override IDs, roles or trusted checks. Mapped fields are name, email and tasks **assigned** to that user (`tasks.user_id`). Task fields include ID, name, description and timestamps. The host's user_id represents an assignee, not necessarily a creator. Unassigned tasks and tasks assigned to other users are excluded, including for configured administrators. Passwords, tokens and other users' profiles are excluded. The original global task-list/assignment behavior is unchanged.

Consent writes persist separately. All other responses are decisions and proposed action plans: account/task modifications, deletion, transfers, complaint delivery, audit writes and notifications are not executed. Access returns metadata; portability returns selected real values. Synthetic metadata and passing checks do not establish legal compliance.

The app has no native administrator role. Breach assessment is disabled by default. A server operator may explicitly configure trusted account IDs in `GDPR_ADMIN_USER_IDS` (comma-separated) in `.env`; client permissions are never accepted. The disposable test fixture configures only its seeded demo account for this test branch.

### Verify independently

```sh
php integration/test.php
```

Optional `PHP_BINARY`, `PHP_INI` and `OPA_BINARY` select runtimes. The native PHP runner starts the actual Laravel HTTP app and OPA using temporary SQLite, a random application key, cookie and ports. It applies original migrations/seeders, uses original registration/login/logout/task handlers, tests all policies and cross-user task isolation, and cleans its temporary fixture/processes afterward. It does not use your configured database or browser login session. Mail uses the array transport. No extra testing package or Python is required and no evaluation report files are created in the repository.