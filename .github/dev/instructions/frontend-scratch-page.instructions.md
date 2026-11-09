# Scratch front-end page

Use this when a front-end test is needed and **no existing page renders the thing under test**.

The question is not "did the user name a page?" but "what is the target, and where does it render?". The target is often not a page: a file the user names may be a PHP class, template, script, or package asset. These only run inside some host page.

1. Identify what the named file is and what it does (read the file; check what it extends or exports). **Determine whether it has a PHP + JS cooperative setup**: does the PHP side register, import, or emit anything that JS depends on (script calls, import map, init code, custom elements, `data()`), or does the JS side expect PHP to set something up? If yes, read [PHP to browser script pipeline](../docs/php-js-script-pipeline.md) before testing. Then check the existing instructions and docs under `.github/dev/` for that kind of thing, and let them decide how to handle it (which procedure, build step, or init script applies) before choosing a test approach.
2. Find who uses it (`grep` for its class name, tag name, or import).
3. If a real page already renders it, test there. If none does, use this scratch page. Never hand-write standalone HTML.

The scratch page should load what the target needs to run (init scripts, asset registration, script helpers). **Prefer the target itself to set this up**: if the target can initialize itself or load its own scripts when used normally (for example its PHP side registers the init script while rendering), build the test environment by using the target the normal way. Do not hand-write its markup or copy its init code, unless rewriting is actually necessary (and then say why). Hand-written markup skips that built-in setup, so the target silently does nothing and the test is invalid. How this setup works (and how to check and rebuild it) is in [PHP to browser script pipeline](../docs/php-js-script-pipeline.md). Only when the target does not set itself up, find what it needs from how real code uses it. Either way, first confirm the target is active (for example, its element upgraded or its script ran) before judging behavior.

1. Generate the view and route, using the stage and view name you choose:

   ```bash
   php windwalker g view <Stage>/<ViewName>
   php windwalker g route <stage>/<view>
   ```

2. Open the generated route file and fill in the view class name, so the route renders that view.
3. Put test HTML and JS in the generated empty files, there may be a blade file or ts file can put your test code, then open the route in the browser (see [e2e-browser-test.instructions.md](e2e-browser-test.instructions.md)).
4. When the test is done, delete the generated view, route entry, and files, and remove any leftover scratch code. Do not leave the scratch page in the repository.
