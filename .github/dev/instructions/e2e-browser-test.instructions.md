# Browser testing with Playwright

When a task needs real browser interaction (clicks, dialogs, forms) to reproduce or verify front-end behavior, use the [e2e-browser-test skill](../skills/e2e-browser-test/SKILL.md).

- Login state lives in `.env.agent.local` (`TEST_<STAGE>_USERNAME/PASSWORD/SESSION_ID`); use it first if it works, otherwise ask the user which login method to use (credentials, session ID, session bridge, temp user). Never put credentials in instructions, skills, docs, or memory.
- curl checks need a browser User-Agent (`-A`), or the session is ignored as a robot.
- Session cookies: front `WINDWALKER_SESSID`, admin `WINDWALKER_ADMIN_SESSID`.
- Never read the user's browser cookies or profile.
- Clicking Save or other submit buttons writes real data. Prefer checks that do not submit; if a test must submit, record the created record IDs and delete them (and only them) afterwards.
- Scripts and logs go under `tmp/e2e/`; delete them and stop the server you started when done.
- If the user did not name a page to test on the front end, you may build a temporary scratch page: see [frontend-scratch-page.instructions.md](frontend-scratch-page.instructions.md). Delete it when the test is done.
