// EXAMPLE ONLY: copy to tmp/e2e/, then adapt URLs, selectors and assertions to the project/scenario.
// Run from the repo root: node tmp/e2e/login.example.mjs
import { chromium } from 'playwright-core';
import { existsSync, readFileSync } from 'node:fs';

const BASE = 'http://localhost:8089';
const STAGE = 'admin'; // 'admin' | 'front'
const COOKIE = { admin: 'WINDWALKER_ADMIN_SESSID', front: 'WINDWALKER_SESSID' }[STAGE];
const LOGIN_URL = { admin: '/admin/login', front: '/login' }[STAGE];
const AUTH_FILE = 'tmp/test.auth.json';

if (!existsSync(AUTH_FILE)) {
  // Ask the user for credentials or a session ID, then write the file (see SKILL.md).
  throw new Error(`${AUTH_FILE} not found`);
}
const auth = JSON.parse(readFileSync(AUTH_FILE, 'utf8'))[STAGE] ?? {};

const browser = await chromium.launch({ channel: 'chrome', headless: true });
const context = await browser.newContext();
const page = await context.newPage();

const dialogs = [];
page.on('dialog', (d) => { dialogs.push(d.message()); d.dismiss(); }); // native alert/confirm

if (auth.session_id) {
  await context.addCookies([{ name: COOKIE, value: auth.session_id, domain: 'localhost', path: '/' }]);
}

await page.goto(`${BASE}/admin/article/list`);

// Not logged in (session missing/expired): fall back to email + password.
if (page.url().includes('/login')) {
  if (!auth.email || !auth.password) {
    throw new Error('Session invalid and no email/password in auth file; ask the user.');
  }
  await page.goto(`${BASE}${LOGIN_URL}`);
  await page.fill('#input-user-email', auth.email);
  await page.fill('#input-user-password', auth.password);
  await Promise.all([page.waitForLoadState('load'), page.click('#login-form .login-button')]);
  await page.goto(`${BASE}/admin/article/list`);
}

await page.waitForLoadState('networkidle');

// Scenario: click delete with nothing checked.
await page.click('button:has(.fa-trash)');
await page.waitForTimeout(1500);

console.log('checked:', await page.locator('input[data-role=grid-checkbox]:checked').count());
console.log('native dialogs:', dialogs);

await browser.close();
