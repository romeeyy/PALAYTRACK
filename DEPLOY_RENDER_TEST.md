# PalayTrack Test Deployment: Render + Supabase

This setup creates a completely separate test environment:

- Local XAMPP continues using the existing local database.
- Render runs the Laravel application.
- Supabase stores test-only PostgreSQL data.

Do not copy the local `.env` to Render and do not commit any passwords.

## 1. Create the Supabase test database

1. Create a project named `palaytrack-testing`.
2. Prefer the Singapore region when available.
3. Open **Connect** and copy the **Session Pooler** connection string on port `5432`.
4. Replace the password placeholder with the database password.
5. If the password contains reserved URL characters, URL-encode it before placing it in the URL.

The final secret looks similar to:

```text
postgresql://postgres.PROJECT_REF:PASSWORD@REGION.pooler.supabase.com:5432/postgres
```

Do not manually create application tables. The Render startup script runs all Laravel migrations.

## 2. Generate the Laravel application key

Run locally:

```bash
php artisan key:generate --show
```

Copy the full `base64:...` value. Do not place it in a committed file.

## 3. Push the deployment files to GitHub

Review and commit the current changes, then push the branch connected to Render.

The real `.env` remains ignored by Git and stays local.

## 4. Create the Render Blueprint

1. In Render, select **New > Blueprint**.
2. Connect the `PALAYTRACK` GitHub repository.
3. Render detects `render.yaml`.
4. Enter the required secret values:

| Variable | Value |
| --- | --- |
| `APP_KEY` | Output of `php artisan key:generate --show` |
| `APP_URL` | The final `https://...onrender.com` service URL |
| `DB_URL` | Supabase Session Pooler URL |
| `INITIAL_OWNER_EMAIL` | A test-only owner email |
| `INITIAL_OWNER_PASSWORD` | At least 8 characters with a letter and number |

The deployment automatically:

1. Installs production Composer packages.
2. Builds Vite assets.
3. Starts Apache on Render port `10000`.
4. Caches Laravel configuration.
5. Runs `php artisan migrate --force`.
6. Creates the first Owner only when no Owner exists.
7. Caches Blade views.

## 5. First login and smoke test

Log in using `INITIAL_OWNER_EMAIL` and `INITIAL_OWNER_PASSWORD`, then test:

1. Owner login and dashboard.
2. Create one Staff account.
3. Add one Rice Type.
4. Record a sample delivery.
5. Move it through Pending, Processing, Completed, Payment, and Claimed.
6. Check inventory, receipt, reports, transactions, notification bell, and dark mode.

`SMS_MODE=simulation` is enabled, so testing does not send a real SMS.

## 6. Test-environment limitations

- Render Free Web Services spin down when idle, so the first request can be slow.
- Supabase Free projects can pause after inactivity.
- Profile pictures are currently stored inside the web container and can disappear after a Render restart or deploy. This is acceptable for temporary testing only.
- Free database projects should be backed up manually if the test data becomes important.

## 7. Safety rules

- Never change the local `.env` to the Supabase URL unless intentionally testing Supabase from local Laravel.
- Never run `migrate:fresh` against the existing local database or any database containing important records.
- Never commit `.env`, database passwords, `APP_KEY`, or Semaphore API keys.
- Keep the existing local database backup until the hosted system is fully verified.
