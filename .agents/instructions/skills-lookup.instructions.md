---
applyTo: '**'
---
# 技能查找指引

開始任務前，先依任務類型判斷相關技能，並閱讀對應的 `SKILL.md` 與 references，再開始實作、回答或審查。同一任務可能涉及多個技能，應全部載入。

## 專案可用知識搜尋指引

- Windwalker 官方文件：
  - LLMS.txt <https://windwalker.io/llms.txt>
  - Search API <https://docs-search.windwalker.io/search> - POST JSON: `{"messages": [{"role": "user","content": "Search text..."}]}`
  - Chat API <POST https://docs-search.windwalker.io/chat/completions> - OpenAI Chat Completions API
  - MCP tool `windwalker-docs-search`
- Confluence / LYRASOFT Wiki 搜尋：
  - MCP tool `com.atlassian/atlassian-mcp-server`
- GitHub 搜尋 `lyrasoft` org 歷史程式碼：
  - MCP tool `io.github.github/github-mcp-server`

## 撰寫、修改、執行或審查測試

撰寫、修改、執行或審查測試（PHPUnit、Windwalker / PHP、E2E）

- @./review.instructions.md 代碼審查指引與安全審查指引。
- @./test.instructions.md 測試指引。
- @../skills/windwalker/references/test/testing.md Windwalker 測試指引。

## PHP / Windwalker 開發工作

Windwalker 框架開發（MVC、DI、CLI、Vue、Unicorn JS 與 PHP 協作）

- @./windwalker.instructions.md Windwalker 開發指引。
- @./cli.instructions.md CLI 可用指令指引。
- @./cs.instructions.md 程式碼規範指引。
- @./services.instructions.md Windwalker 服務組織指引。
- @../skills/windwalker/SKILL.md 查看 Windwalker 開發技能清單。
- @../skills/lyrasoft-best-practice/SKILL.md 查看 LYRASOFT 最佳實踐技能清單。

### 新建後台 MVC / Entity / Migrations 等

- @../skills/windwalker/references/development-app/mvc-generation.md MVC 自動化建立指引。
- @../skills/windwalker/references/knowledge/di-injection.md DI 注入指引。
- <https://windwalker.io/documentation/framework/packages/package-override.md> 套件覆寫指引。

### Windwalker 內前端開發與套版

Windwalker 內進行前端 js 開發或者 HTML / Blade / CSS / SCSS / Vue 套版

- @./assets.instructions.md 前端資產與建置指引。
- @./cs.instructions.md 程式碼規範指引。
- @../skills/lyrasoft-best-practice/references/cs/css-cs.md CSS / SCSS 規範指引。
- @../skills/lyrasoft-best-practice/references/styling/design-system.md Design System 切版標準規範。
- @../skills/lyrasoft-best-practice/references/organizing/css-scss-organizing.md CSS / SCSS 組織規範指引。
- @../skills/lyrasoft-best-practice/references/styling/bootstrap-scss.md Bootstrap / SCSS 規範指引。
- @../skills/windwalker/references/development-app/vue-development.md Vue 開發指引。

### Windwalker / Unicorn / Luna 內部套件開發

若要修改 Windwalker Components / Core / Unicorn / Luna 套件，或者要開發新的 Packages 時，可以參考。

- @./architecture.instructions.md 整體專案架構指引。
- @../skills/windwalker/references/development-core/package-development.md 套件開發指引。

## 建立或編輯 instructions / skills / docs 時

- @./instruction.instructions.md instructions / skills / docs 編寫指引。

## 不確定的任務類型

請閱讀所有 instructions 與相關技能的 `SKILL.md` 與 references，並在回覆中說明你認為最適合的任務類型與技能，並說明理由。

## 任務類型對應

- @../instructions 查看完整指引清單。
- @../skills/index.md 查看完整技能清單。

## 原則

- 同時符合多種任務類型時，全部相關技能都要載入，不要只取其中一個。
- 技能與專案指引衝突時，以專案內的指引為準，並在回覆中說明差異。
- 只載入與當前任務相關的 references，避免一次讀取整個技能資料夾。
- 新增技能後，請同步更新 `skills/index.md` 與本指引的對應表。
