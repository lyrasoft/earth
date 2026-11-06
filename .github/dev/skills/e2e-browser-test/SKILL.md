---
name: e2e-browser-test
description: Reproduce or verify front-end behavior that needs real clicks by driving local Chrome with Playwright against the PHP dev server, including the admin/front login via tmp/test.auth.json.
---

# Browser (Playwright) testing

## Purpose

Verify front-end behavior that needs real interaction (grid toolbar buttons, dialogs, forms) in a real browser against the local dev server.

## Prerequisites

- `playwright-core` is a root devDependency. It downloads no browsers; launch the installed Chrome with `channel: 'chrome'` (Edge: `channel: 'msedge'`). Do not run `yarn install`; ask the user if packages are missing.
- Put scripts and logs under `tmp\e2e\` (ignored by git). Run from the repo root: `node tmp\e2e\<script>.mjs`.
- Commands are documented elsewhere; use them as written: [fusion-assets](../../instructions/fusion-assets.instructions.md), [cli](../../instructions/cli.instructions.md), [unicorn-scripts](../../instructions/unicorn-scripts.instructions.md).

## Workflow

1. **Build the change** if it is in package JS (unicorn-next / core assets): follow "Building and testing package JS" in [unicorn-scripts](../../instructions/unicorn-scripts.instructions.md) (package build, root `yarn build`, install task). Confirm the changed code exists in `www/assets/vendor/@windwalker-io/unicorn-next/dist/` and `www/assets/chunks/` first; otherwise the browser runs old code.
2. **Start the PHP server on an explicit port** and record its PID:

   ```powershell
   Start-Process php -ArgumentList 'windwalker','server:start','php:main','--host','localhost','--port','8089' `
     -RedirectStandardOutput tmp\e2e\server.out -RedirectStandardError tmp\e2e\server.err -PassThru
   curl.exe -s -o NUL -w "%{http_code}" http://localhost:8089/   # expect 200
   ```

   The PID returned can be the launcher, not the listener. Find the real one with `Get-NetTCPConnection -LocalPort 8089 -State Listen` and stop only that (`Stop-Process -Id <PID>`).
3. **Choose a login method** (see "Choosing a login method"), copy `assets/login.example.mjs` to `tmp\e2e\`, adapt it, and run it.
4. **Clean up**: delete the `.mjs` scripts, logs, and stop only the server you started. Keep `tmp/test.auth.json`.

## Choosing a login method

Before the first test that needs a login:

1. **Try `tmp/test.auth.json` first.** If it has a usable entry for the stage (a `session_id` that still works, or email + password), use it without asking.
2. Otherwise (file missing, stage empty, or the entry fails) **ask the user which method to use** (ask-user tool, one question):
   - **Email + password** → write to `tmp/test.auth.json`, log in through the form.
   - **Session ID** → write to `tmp/test.auth.json`, inject the cookie.
   - **Session bridge** (forge a session for an existing user via the DB; needs `phpunit.xml`/`.env` DB access) → see "Option: session bridge".
   - **Temporary test user** (last resort) → see "Fallback: temporary test user".
3. Never print credentials back or copy them into instructions, skills, docs, or memory.

## Auth file: `tmp/test.auth.json`

Login state for tests lives in `tmp/test.auth.json` (ignored by git). It is plain JSON, so the user can edit it at any time.

```json
{
  "admin": { "email": "", "password": "", "session_id": "" },
  "front": { "email": "", "password": "", "session_id": "" }
}
```

Rules:

1. If the user chooses email/password or a session ID (see "Choosing a login method"), write only that to the file under the right stage. Never invent credentials.
2. On later runs read the file. Prefer `session_id` when set; otherwise log in with email and password.
3. If a stored session ID no longer works (redirected to the login page), fall back to email and password if present; otherwise ask the user again and update the file.
4. Do not read the user's browser cookies or profile. Only use what the user gave you.

### Session cookie names

| Stage | Cookie name | Login URL |
| --- | --- | --- |
| Front | `WINDWALKER_SESSID` | `/login` (site dependent) |
| Admin | `WINDWALKER_ADMIN_SESSID` | `/admin/login` |

Use a session ID with `context.addCookies([{ name, value, domain: 'localhost', path: '/' }])` before opening pages. Cookies are shared across ports on `localhost`, but the session must exist in the same session store the server uses.

### Admin login form (verified)

Fields `#input-user-email`, `#input-user-password`; submit `#login-form .login-button`; success redirects to `/admin`. Other projects may differ, so inspect the page before reusing selectors.

## Example script

`assets/login.example.mjs` logs in (session ID or credentials from `tmp/test.auth.json`), opens an admin list page, clicks the delete toolbar button with nothing checked, and prints the native dialogs. **It is an example: adjust the URLs, selectors, and assertions for the project and the scenario you are testing.**

## Notes

- Without any checked row the delete button should show exactly one dialog (`Please check one or more items.`); before the `deleteList()` fix it also showed the confirm dialog.
- Luna admin toolbars have no `.uni-btn-delete`; select with `button:has(.fa-trash)`. Generated MVC lists do have `.uni-btn-delete`.
- `simpleAlert` / `simpleConfirm` use native dialogs in this setup. Dismiss them inside the `dialog` handler or the click blocks.
- Wait for `networkidle` before clicking; Alpine and unicorn initialize after load.

## Testing with curl (quick non-browser checks)

`curl` is fine for checking that a page returns 200 (not a redirect to login), but:

- **Send a browser User-Agent.** `SessionRobotSubscriber` disables the session for robot UAs, so the default `curl/x.x` UA is treated as logged out even with a valid session cookie (verified: 303 → 200 once `-A` was added).
- Cookie attributes (domain, path, httponly, samesite) do not matter for `-b "NAME=value"`; it sends a raw header.
- Use `curl.exe` in PowerShell, and put the admin cookie name `WINDWALKER_ADMIN_SESSID` for `/admin/*`.

```powershell
curl.exe -s -o NUL -w "%{http_code} %{redirect_url}`n" `
  -A "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36" `
  -b "WINDWALKER_ADMIN_SESSID=$id" http://localhost:8089/admin/article/list
```

Clicks, dialogs, and JS behavior still need Playwright.

## Option: session bridge (forge a session for an existing user)

Use when the user has no session ID to give and password login is undesirable. `assets/session-bridge.example.php` writes a session row for an **existing** user (never changes passwords), prints its ID, and deletes it afterwards.

1. Copy it to `tmp\e2e\session-bridge.php` and adapt (root path, DB driver, user email).
2. `php tmp\e2e\session-bridge.php create <email> admin` → session ID; put it in the cookie (`WINDWALKER_ADMIN_SESSID` for admin) or `tmp/test.auth.json`.
3. When done: `php tmp\e2e\session-bridge.php destroy <session_id>`, clear `session_id` in the json, delete the copy.

Facts (verified): the store follows `SESSION_DEFAULT` (`.env.base` sets `database` → `sessions` table with `id, data, user_id, stage, time`; default would be `<tmp>/sess/sess_<id>`). Data is `serialize(['login_user_id' => <int>])`; `user_id` and `stage` (`admin`/`front`) should also be set. The user must be `enabled=1`, `verified=1`, with a superuser/admin role. DB settings come from `phpunit.xml` `WINDWALKER_TEST_DB_DSN` (preferred; parsed for host/dbname/user/password) and fall back to `.env.base`/`.env.dev`/`.env` `DATABASE_*`. Confirm the DSN's `dbname` is the app database, not a framework test DB. Never print the password. A forged cookie worked in Playwright/Chrome; plain `curl` was redirected to login, so verify in the browser.

## Fallback: temporary test user

If the user cannot provide credentials or a session, you may create a temporary user in the database (get the connection from the user's `phpunit.xml`/`.env`, only use it to connect, never print the password). Delete it afterwards; do not edit the existing admin's password hash. Requirements:

- Add a `user_roles` row with the **superuser** role (preferred over `admin`, because permissions differ greatly between projects). At least `admin` or `superuser` is required to enter the backend.
- `enabled` must be `1` and `verified` must be `1`.
- A newly created user may have no data of its own (profile, related records); prepare what the page needs.
- Hash the password with PHP (`password_hash`) at runtime; do not store a fixed hash in the repository.
- Write the new account into `tmp/test.auth.json` like any other credentials, then remove the user when finished.
