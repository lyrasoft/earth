# PHP / Windwalker 測試指引

## 測試指令

- 專案通常會安裝 `phpunit`，可使用 `./vendor/bin/phpunit` 執行測試。
- 專案若有 `phpunit.dist.xml` 或 `phpunit.xml`，可直接使用作為 configuration
- 專案若需資料庫，可從 phpunit.xml 取得資料庫連線資訊

## 測試環境

- `tests/` 目錄下放置測試檔案，結構盡量接近 `src/` 目錄結構，方便對應。
- `tests/` 目錄下通常有 `TestApp` 與 `bootstrap.xx.php`，用於測試環境初始化與共用設定。

## 其他

- 若需要 smoke test 或編寫臨時性測試文件，盡量放置於專案的 `tmp/` 目錄下。見 @smoke-test.md
- 測試需要暫存檔案或目錄時：
  - 用專案 `tmp/`（`WINDWALKER_TEMP`，預設 `WINDWALKER_ROOT . '/tmp'`），不用 `sys_get_temp_dir()`／`tempnam()`，也不放專案根目錄。
  - 路徑如 `WINDWALKER_TEMP . '/ww-test-' . uniqid()`。
  - 在 `#[Before]` 建立，在 `#[After]`（或 shutdown function）移除。
- App 測試（層級、自動重置、request 範圍、輔助 trait）見 @app-testing.md

## 文件

- Windwalker 提供測試輔助功能，請預先觀看: https://windwalker.io/documentation/framework/services/testing.md
- Windwalker 文件搜尋可使用 MCP `windwalker-docs-search`，或查閱公開文件索引: https://windwalker.io/llms.txt
- PhpUnit 文件請見 @phpunit-testing.md
- 其他相關文件請參考專案的 instructions 或 docs/ 目錄。

## 相關文件

- [Test 元件](https://windwalker.io/documentation/components/test/index.md)
