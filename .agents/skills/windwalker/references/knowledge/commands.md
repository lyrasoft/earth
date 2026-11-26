# Command-Line Interface (CLI)

## All commands

The following are the commands available by default in an installed host app.

```shell
Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
      --silent          Do not output any message
  -q, --quiet           Only errors are displayed. All other output is suppressed
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  auto-complete       Create completion script.
  completion          Dump the shell completion script
  exec                Start a REPL console or exec code.
  help                Display help for a command
  list                List commands
  run                 Run custom scripts.
 asset
  asset:version       Create assets version file.
 build
  build:entity        Build entity getters/setters and sync properties with database.
  build:enum          Build enum docblock.
  build:form          Build form definition from DB table.
 cache
  cache:clear         Clear cache by folders.
  cache:purge         Clear cache by profiles.
  cache:table         Create cache migration file.
 config
  config:revise       Revise config files.
 crypt
  crypt:secret        Generate random key.
 db
  db:drop-all         Drop all database
  db:export           Export database to file.
  db:ping             Ping the database to check it is live.
 generate
  generate:make       [g] Generate files.
  generate:revise     Revise file to template.
 lang
  lang:export         Export language ini to excel files.
  lang:import         Import language sheet (xls, xlsx, csv) to ini files.
  lang:merge          Merge language files.
 mail
  mail:test           This command will send a test mail by your mail settings.
 mig
  mig:back            Migrate back some steps.
  mig:create          Create a migration version.
  mig:from            Migrations from excel files
  mig:go              Migrate to specific version or latest.
  mig:reset           Reset migration versions.
  mig:squash          Squash current database schema as squashed.
  mig:status          Show migration status.
 pkg
  pkg:install         Install package resources.
 queue
  queue:enqueuer      Start an enqueuer.
  queue:failed-table  Create failed jobs migration file.
  queue:restart       Send restart signal to all workers.
  queue:retry         Retry failed jobs.
  queue:table         Create jobs migration file.
  queue:worker        Start a queue worker.
 s3
  s3:vcs              Control S3 Versioning Control System (VCS).
 schedule
  schedule:install    Install CRON schedule
  schedule:run        Run CRON schedule
  schedule:show       Run CRON schedule
  schedule:uninstall  Uninstall CRON schedule
  schedule:worker     Run a resident CRON schedule worker.
 seed
  seed:clear          Clear seeders
  seed:create         Create a seeder.
  seed:import         Import seeders
 server
  server:dumper       Start the dump server to collect dump information.
  server:start        [server:run] Start Windwalker Server.
  server:stop         Stop Windwalker Server.
 site
  site:down           Make website offline to maintenance mode.
  site:up             Make site online.
 task
  task:run            Execute a trusted serialized task and return a JSON result.
 type
  type:data           Generate data / record to .ts file.
  type:entity         Generate entity.ts file.
  type:enum           Generate typescript enum file.
```

To see more commands available in a project, run `php windwalker list` or check `resources/registry/commands.php`.

## Basic usage

- Entry point: `windwalker` in the repository root (`php windwalker`)
- List commands: `php windwalker list`
- Show help: `php windwalker help <command>`
- Diagnostic output: `-v`, `-vv`, `-vvv`
- Automation: `--no-interaction` (provide all required arguments first)
- Requirements: PHP and Composer dependencies (loads `vendor/autoload.php`)

## Configuration and startup

- Startup files: `windwalker` → `etc/define.php` → `etc/runtime.config.php` → `etc/app/console.config.php`
- Command registration: `resources/registry/commands.php`
- Named scripts: `resources/registry/scripts.php` (`php windwalker run <script>`)
- Environment file order: `.env.base`, `.env`, `.env.local`, `.env.<APP_ENV>`, `.env.<APP_ENV>.local` (default `APP_ENV` is `prod`)
- Path aliases: `etc/runtime.config.php` (for example, `@public` → `www/`, `@temp` → `tmp/`)
- The Framework and packages may also provide commands; confirm with `list`.

## Named scripts

- Example: `php windwalker run prepare` (installs frontend dependencies and builds assets)
- Read a script's definition before running it; some scripts modify dependencies, generate files, or change the database.
- `preparedev` resets migrations and imports seed data; do not use it when data must be retained.

## Code generation (`g`)

- List tasks: `php windwalker g`
- Show task details: `--about` (do not use `-h`, which shows help for the outer command)
- Common tasks: `command`, `controller`, `view`, `model`, `entity`, `form`, `route`; packages may add more.
- Register additional tasks in `resources/registry/generator.php`.
- Default output for commands: `src/Command/`; use `--ns` to change the namespace and `--dir` to change the output directory.
- Register a command with `--auto-register <name>` to add it to `resources/registry/commands.php` (optional).
- Avoid `--force` unless you explicitly intend to overwrite existing files.
- Migrations are not part of `g`; use `mig:create` (`php windwalker help mig:create`). Migration files are in `resources/migrations/`.

## Development server

- Commands: `server:start` (alias `server:run`) and `server:stop` (currently supports Swoole only)
- Format: `engine:name`, default `php:main`; engines are `php` or `swoole`.
- Named servers: `resources/registry/servers.php` (`php` and `swoole` are currently empty)
- `--main <file>`: directly specifies the main file and does not select an engine; explicitly specify `engine:name`.
- PHP built-in server: serves `www/` by default; `--docroot` overrides it. The default host is `localhost`; if no port is specified, it finds an available port starting at `8000`.
- The PHP server runs `php -S` and is for local development only, not production deployment.
- Swoole: requires the Swoole extension and `windwalker/reactor`; options include `--app`, `--workers`, `--task-workers`, `--max-requests`, and `--watch`; process state is stored in `@temp/servers/`.
- Reactor templates: `vendor/windwalker/framework/packages/reactor/resources/servers/` (can be published with the `swoole_website` / `swoole_websocket` tags)
- After startup, send an actual request to confirm the server responds.

## Package resources (`pkg:install`)

- Purpose: publish resources from an installed package into this project (does not download Composer dependencies)
- Help: `php windwalker help pkg:install`
- Specify a package and tag: `php windwalker pkg:install windwalker/reactor --tag swoole_website`
- Preview: add `--dry-run`
- `--details=3` lists every copied file (level 3 is the default)
- `--all` must be used with an explicit package name.
- Overwrite existing files with `--force` only when you are certain.
- After completion, inspect copied files and changes made by callbacks (including registry files); `swoole_websocket` may generate application code.
- When modifying a package implementation, follow the instructions for application / package boundaries.

## References

- [Basic Commands](https://windwalker.io/documentation/framework/console/basic-commands.md)
- [Console Basics](https://windwalker.io/documentation/framework/console/console-basics.md)
- [Server Basics](https://windwalker.io/documentation/framework/cli-servers/server-basics.md)
- [Environment component](https://windwalker.io/documentation/components/environment/index.md)
