# How the Unicorn JS Pipeline and PHP Adapter Work

This document describes how Windwalker Unicorn frontend JS is loaded and how the PHP side passes data and scripts to the frontend.

## Overall flow

- PHP Script objects (such as `UnicornScript`, `BootstrapScript`, `FormScript`, and `EditorScript`) declare which frontend entries to load.
- `init()` registers `@vite` entries. Depending on the mode:
  - Next mode outputs an import map and loads ES modules.
  - Classic mode uses SystemJS.
- `main()` writes data such as URIs, translations, and the CSRF token to the page for the frontend to read.
- The frontend entry (`@main`) loads modules and runs initialization through helpers such as `importMainThen()`, `importThen()`, `importScript()`, and `addInitialise()`.
- Each helper runs only once per Script object and is shared within the same request.

## Frontend app and adapter

- `useUnicorn()` returns a singleton application that provides:
  - Events: `on`, `once`, `off`, `trigger`
  - Dependency injection: `provide`, `inject`, `use`
  - Data: `data()`, `useMacro`
- The `loaded` event fires only once, after DOMContentLoaded.
- Call `useUnicornPhpAdapter()` in the entry file to connect to the PHP side.
- `pushUnicornToGlobal()` exposes `u` for inline scripts.
- Loading packages with `useImport()` requires import map support.
- Do not use both `import` and `useImport()` for the same package, or it will be loaded twice.

## Data transfer

- PHP writes data only once to `document.__unicorn`, before page scripts run.
- Inject `UnicornScript` into the class rendering the page; the same instance is shared for the request.
- PHP write methods:
  - `data(name, value)`: stores arbitrary values. By default, values with the same name are merged; pass `false` as the third argument to replace.
  - `translate('key')` or `translate('prefix.*')`: copies translation strings to `unicorn.languages`; only copy keys needed by the page.
  - `addRoute(name, path)`: registers a named URL.
  - `exposeCspNonce()`: call when using a CSP nonce; read it with `data('csp-nonce')`.
- JS read methods:
  - `data(name)`: reads data, such as `data('user.tags')`; the CSRF token is `data('csrf-token')`.
  - `trans(key)`: reads a translation string.
  - `route(name, query)`: generates a route URL; throws an error if the name is not registered.
  - `useSystemUri(part)`: gets `full`, `path`, `root`, `current`, `route`, or `script`. `useAssetUri()` reads asset paths.
- Values written by JS with `data(name, value)` remain in the browser and are not sent back to PHP.
- This data appears as JSON in the page source and can be read by anyone who can load the page. Do not include passwords, API keys, or private records.

Example (PHP):

```php
$this->unicornScript->translate('user.edit.*');
$this->unicornScript->addRoute('user_delete', '/admin/user/delete');
$this->unicornScript->data('user.tags', $selectedTags);
```

Example (JS):

```ts
import { data, route, trans } from '`windwalker-io/`unicorn-next';

const tags = data('user.tags');
const label = trans('user.edit.save');
const url = route('user_delete', { id: 5 }); // /admin/user/delete?id=5
```

## Theme and UI adapter

- Unicorn's layout resolves the `@theme` alias; the default theme is `views/ui/bootstrap5`.
- Bootstrap JS is loaded through `useUITheme()` / `UIBootstrap5`.
- A custom adapter must implement `renderMessage()` and `clearMessages()`.

## Editors

- `useTinymce(selector)` and `useNuxtEditor(selector)` return controllers with `getValue`, `setValue`, `insert`, and `setEditable`.
- Calling either with the same selector returns the same controller.
- TinyMCE options are merged into `tinymce.init()`.
- Upload events are `upload-start`, `upload-success`, `upload-error`, and `upload-complete`.
- `TinymceEditorField` extends `TextareaField`:
  - The toolbar has `full` and `simple` variants by default.
  - Configure it with `editorOptions()`, `uploadProfile()`, `imageUploadUrl()`, and `stackName()` (defaults to `uploading`).
  - HTML is not sanitized on the server; handle it yourself.
- `NuxtEditorField` is based on TipTap / Nuxt UI `UEditor`:
  - Formats can be `html`, `markdown`, or `json`.
  - Configure it with `NuxtFieldOptions` and `configureOptions()`; creation triggers `nuxt-editor:create`.

## References

- [Script loading](https://windwalker.io/documentation/unicorn/services/script-loading.md)
- [Unicorn JS](https://windwalker.io/documentation/unicorn/javascript-frontend/unicorn-js.md)
- [Data Transfer](https://windwalker.io/documentation/unicorn/javascript-frontend/basics/data-transfer.md)
- [Translation](https://windwalker.io/documentation/unicorn/javascript-frontend/utilities/translation.md)
- [Theme configuration](https://windwalker.io/documentation/unicorn/introduction/theming.md)
- [Editors](https://windwalker.io/documentation/unicorn/javascript-frontend/ui/editors.md)
- [TinyMCE editor field](https://windwalker.io/documentation/unicorn/form-fields/field-types/tinymce-editor.md)
- [Nuxt editor field](https://windwalker.io/documentation/unicorn/form-fields/field-types/nuxt-editor.md)
