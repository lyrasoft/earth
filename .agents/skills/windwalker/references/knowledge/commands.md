# 命令列介面（CLI）

## 所有指令

以下列出 host app 安裝起來預設的指令。

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

若有需要查看專案更多專案指令，請使用 `php windwalker list` 或者查看 `resources/registry/commands.php` 。

## 基本用法

- 入口：repository 根目錄的 `windwalker`（`php windwalker`）
- 列出指令：`php windwalker list`
- 查看說明：`php windwalker help <command>`
- 診斷輸出：`-v`、`-vv`、`-vvv`
- 自動化：`--no-interaction`（需先提供所有必要參數）
- 需求：PHP 與 Composer 相依套件（載入 `vendor/autoload.php`）

## 設定與啟動

- 啟動檔：`windwalker` → `etc/define.php` → `etc/runtime.config.php` → `etc/app/console.config.php`
- 指令註冊：`resources/registry/commands.php`
- 具名腳本：`resources/registry/scripts.php`（`php windwalker run <script>`）
- 環境檔順序：`.env.base`、`.env`、`.env.local`、`.env.<APP_ENV>`、`.env.<APP_ENV>.local`（預設 `APP_ENV` 為 `prod`）
- 路徑別名：`etc/runtime.config.php`（例如 `@public` → `www/`、`@temp` → `tmp/`）
- Framework 與 package 也可能提供指令，以 `list` 確認

## 具名腳本

- 範例：`php windwalker run prepare`（安裝前端相依套件並建置資產）
- 執行前先閱讀腳本定義，部分會修改相依套件、產生檔或資料庫
- `preparedev` 會重設 migration 並匯入種子資料，勿用於需保留的資料

## 程式碼產生（`g`）

- 列出任務：`php windwalker g`
- 任務說明：`--about`（勿用 `-h`，那是外層指令的說明）
- 常見任務：`command`、`controller`、`view`、`model`、`entity`、`form`、`route`，package 可能新增
- 額外任務註冊：`resources/registry/generator.php`
- command 預設輸出：`src/Command/`；`--ns` 改 namespace，`--dir` 改輸出目錄
- 註冊 command：`--auto-register <name>`，寫入 `resources/registry/commands.php`（選用）
- 避免使用 `--force`，除非明確要覆寫既有檔案
- Migration 不屬於 `g`，使用 `mig:create`（`php windwalker help mig:create`），檔案位於 `resources/migrations/`

## 開發伺服器

- 指令：`server:start`（別名 `server:run`）、`server:stop`（目前僅支援 Swoole）
- 格式：`engine:name`，預設 `php:main`；engine 為 `php` 或 `swoole`
- 具名伺服器：`resources/registry/servers.php`（`php` 與 `swoole` 目前為空）
- `--main <file>`：直接指定 main 檔，不會選擇 engine，需明確指定 `engine:name`
- PHP 內建伺服器：預設服務 `www/`，`--docroot` 可覆寫；預設 host `localhost`，未指定 port 時從 `8000` 起找可用 port
- PHP 伺服器會呼叫 `php -S`，僅供本機開發，非正式部署
- Swoole：需要 Swoole 擴充套件與 `windwalker/reactor`；選項含 `--app`、`--workers`、`--task-workers`、`--max-requests`、`--watch`；程序狀態存於 `@temp/servers/`
- Reactor 範本：`vendor/windwalker/framework/packages/reactor/resources/servers/`（可用 `swoole_website` / `swoole_websocket` tag 發布）
- 啟動後以實際請求確認伺服器有回應

## Package 資源（`pkg:install`）

- 用途：將已安裝 package 的資源發布到本專案（不下載 Composer 相依套件）
- 說明：`php windwalker help pkg:install`
- 指定 package 與 tag：`php windwalker pkg:install windwalker/reactor --tag swoole_website`
- 預覽：加上 `--dry-run`
- 除錯：`--details=3` 列出每個複製的檔案（預設即為 level 3）
- `--all` 需搭配明確的 package 名稱
- 覆寫既有檔案：`--force`，僅在確定需要時使用
- 完成後檢查複製的檔案與 callback 產生的變更（包括 registry）；`swoole_websocket` 可能產生應用程式程式碼
- 修改 package 實作時，遵循 instructions 的應用程式／package 邊界

## 參考連結

- [內建指令（Basic Commands）](https://windwalker.io/documentation/framework/console/basic-commands.md)
- [Console 基礎（Console Basics）](https://windwalker.io/documentation/framework/console/console-basics.md)
- [伺服器基礎（Server Basics）](https://windwalker.io/documentation/framework/cli-servers/server-basics.md)
- [Environment 元件](https://windwalker.io/documentation/components/environment/index.md)
