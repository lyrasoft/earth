# AssetService and Vite

## Purpose and boundary

[`AssetService`](../../../vendor/windwalker/core/src/Core/Asset/AssetService.php) is the runtime asset registry and HTML renderer. It registers CSS/JavaScript links and inline code, resolves asset URLs, and renders tags into a page. It does not compile source files. Compilation is a separate frontend build step documented in [Vite and Fusion assets](../instructions/fusion-assets.instructions.md).

`AssetService` receives a [`ViteResolver`](../../../vendor/windwalker/core/src/Core/Asset/ViteResolver.php), but remains useful when Vite is not running and no Vite manifest exists. In that case it uses its own configured asset paths and file lookup rules.

## How it cooperates with Vite

`ViteResolver` integrates with the service through two runtime signals:

- The dev-server file (by default `tmp/vite-server`) indicates a running server and provides its URL.
- The Vite manifest (by default `@public/assets/manifest.json`, which is `www/assets/manifest.json` in this host) maps source entry paths to emitted files and CSS.

When `AssetService` handles a URI beginning with `@vite` or `~/`, `ViteResolver` resolves it only if either signal is available. `~/` is normalized to the `@vite/` alias, whose base defaults to `resources/assets`. With a dev server, the resolver prefixes the server host and path. Without a dev server, it looks up the corresponding manifest entry and returns its emitted `file`; if there is no manifest entry, it leaves the URI unchanged.

`viteEntry()` registers the JavaScript entry and adds CSS paths from that entry's manifest `css` list. Without a manifest, no CSS entry list can be discovered, so use ordinary CSS registration or a configured entry as appropriate. While the dev server is running, `renderJS()` also emits the `@vite/client` module script, and dev-server links skip the normal version query string.

| Runtime state | Vite URI resolution | `viteEntry()` CSS discovery | `renderJS()` adds Vite client |
| --- | --- | --- | --- |
| Dev server running | `@vite/...` resolves to the dev-server URL | No manifest CSS lookup while server is active | Yes |
| No server, manifest available | Source entry resolves to the manifest's emitted `file` | Yes, from the manifest entry's `css` list | No |
| No server and no manifest | Vite aliases are not resolved; URI falls through to normal asset handling | No | No |

Vite awareness is opt-in at the URI level: ordinary URLs such as `css/site.css` are handled by `AssetService`'s normal path resolver even when a Vite manifest exists.

## Behavior without a Vite server or manifest

The service still:

- Registers and renders CSS and JavaScript URLs, including absolute/CDN URLs, custom HTML attributes, async and module scripts, footer styles, version queries, and SRI attributes.
- Resolves configured asset aliases and top-level import-map names. It can also build/render browser import maps with scoped imports. `has()` checks whether a local asset exists and returns its resolved URL or `false`.
- Resolves paths against the configured public asset folder (default `assets`) and system URI. For local `handleUri()` lookups, debug mode prefers the unminified file; production mode prefers the `.min` file; either mode falls back to the other variant if only one exists. If neither exists, it returns the requested URL with the asset base instead of compiling or copying a file.
- Adds version query parameters when enabled. The version may be set explicitly, read from the configured version file, or detected from asset file modification times and the application secret. Absolute asset bases derive a version from the base URL and secret.
- Registers inline CSS/JS content, including lazily evaluated content, and separates footer CSS and module JS. Internal output is opt-in on `renderCSS()`/`renderJS()` and can receive HTML attributes such as a CSP nonce.
- Emits a before-render event so listeners can modify the links, initial HTML, internal-code flag, or attributes before tags are generated.
- Provides import-map rendering, app-module import helpers, JavaScript object serialization, and named teleport output buffers for collecting HTML fragments.

For module import helpers such as `importByApp()` and `importSyncByApp()`, the service emits runtime JavaScript using the configured module handlers; this is separate from Vite compiling an entry bundle.

## Asset URL and rendering flow

For a registered `css()` or `js()` link, the high-level flow is:

1. `addLink()` passes the URL through alias resolution and `handleUri()`.
2. `handleUri()` optionally delegates a Vite URI to `ViteResolver`; otherwise it resolves the local path, checks `.min`/unminified variants according to debug mode, and applies the configured asset/system URL base.
3. The link is cached by type, resolved URL, options, and attributes to avoid duplicate registrations. `AssetLink` holds the URL/options and HTML attributes.
4. `renderCSS()` or `renderJS()` emits the before-render event, then turns the current registry into `<link>` or `<script>` markup. It applies query versions and SRI attributes and includes internal code only when requested.

The service does not check every registered script URL for file existence at render time. Use `has()` when a caller needs an existence check; when diagnosing a Vite-built entry, inspect `www/assets/manifest.json` and confirm the emitted path exists on disk.

## Configuration and troubleshooting

The constructor reads the `asset` configuration namespace. The asset folder defaults to `assets`; `asset.uri` can override the computed system URI path/root. The Vite resolver reads `asset.vite.base` (default `resources/assets`), `asset.vite.manifest` (default `@public/assets/manifest.json`), and `asset.vite.server_file` (default `tmp/vite-server`).

For a missing or stale asset:

1. Identify whether the registered URI is an ordinary asset URL or a Vite alias (`@vite/...` / `~/...`).
2. For Vite aliases, check the dev-server file first; otherwise verify the source key and emitted `file` in the manifest.
3. For ordinary local URLs, check the configured public asset folder and both the normal and `.min` file variants. Check whether the registration explicitly disables versioning or supplies a custom version.
4. If the correct URL still does not appear in HTML, inspect the render method's `AssetBeforeRender` listeners and verify whether CSS was registered as footer content or internal markup was left disabled.
