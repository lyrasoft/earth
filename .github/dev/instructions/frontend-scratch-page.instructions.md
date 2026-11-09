# Scratch front-end page

Use this when a front-end test is needed but the user did not name a page to test on. you can build a temporary one instead.

1. Generate the view and route, using the stage and view name you choose:

   ```bash
   php windwalker g view <Stage>/<ViewName>
   php windwalker g route <stage>/<view>
   ```

2. Open the generated route file and fill in the view class name, so the route renders that view.
3. Put test HTML and JS in the generated empty files, there may be a blade file or ts file can put your test code, then open the route in the browser (see [e2e-browser-test.instructions.md](e2e-browser-test.instructions.md)).
4. When the test is done, delete the generated view, route entry, and files, and remove any leftover scratch code. Do not leave the scratch page in the repository.
