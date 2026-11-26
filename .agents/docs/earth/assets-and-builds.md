# Earth assets and builds

- The root project uses Yarn `4.x`. Build through the root Yarn scripts and existing dependencies; do not install dependencies just to validate an asset change.
- Treat `www/assets/` as generated output, but preserve uploaded/runtime files and `vendor/` assets when cleaning build output.
- `vendor/windwalker/unicorn` and `vendor/windwalker/core` are separate repositories. Build their asset sources using the owning package's procedure before rebuilding Earth assets; see [Unicorn JS development](unicorn-js-development.md).
- On Windows, if a build reports locked output files, do not terminate a process you did not start. Check whether the manifest and emitted files are current, then ask the user how to handle a still-running watcher if output is stale.
- Do not put registry credentials in instructions, command history, or committed files. If private-registry authentication prevents a requested build, report the blocker rather than changing credentials or installing packages.
