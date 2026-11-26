---
name: windwalker
description: Windwalker 框架的技能，提供 Windwalker 專案開發相關的指令、文件與技能。
---

# Skill: windwalker

## 目的

當 agent 要開始開發新功能時，此 skill 提供 Windwalker 專案開發相關的指令、文件與技能，讓 agent 可以快速上手並遵循專案慣例。

## 使用時機

- 替專案開發新功能或修正程式碼時。
- 檢查或審查專案的程式碼、文件或指令時。

## 範圍

通常用於專案採用 PHP / Windwalker 框架開發時，可能是全 Windwalker Full-stack 專案，也可能是 Windwalker 作為後端，另外建立前端 app 時。一般來說專案根目錄會是 Windwalker 專案，並且有 `www/`、`assets/`、`composer.json`、`.env` 等檔案與資料夾。

## 預期行為

- 確認專案屬於 Windwalker 專案。
- 查閱 instructions 中所有與 Windwalker 專案開發相關的指令與文件，並依照專案慣例執行。
- 依照任務需求，使用本 skill 內的相關 references、文件與指令。

## 相關文件

- Windwalker 官方文件: <https://windwalker.io/llms.txt>
  - Windwalker Search API: <https://docs-search.windwalker.io/search> - POST JSON: `{"messages": [{"role": "user","content": "Search text..."}]}`
  - Chat API <POST https://docs-search.windwalker.io/chat/completions> - OpenAI Chat Completions API
  - Search MCP Tools: `windwalker-docs-search`
- 核心知識 `references/knowledge/`
  - 可用的 CLI 指令與使用方式: `references/knowledge/commands.md`
  - DI 容器與服務註冊: `references/knowledge/di-injection.md`
  - I18n 多國語言環境開發: `references/development-app/i18n.md`
  - Unicorn JS 與 PHP 的協作流程: `references/knowledge/unicorn-js-script-pipeline.md`
  - Vite／Fusion 建置與 AssetService 資產解析: `references/knowledge/frontend-and-assets.md`
- 應用開發 `references/development-app/`
  - 建立 MVC 模組的產生器指令與注意事項: `references/development-app/mvc-generation.md`
  - Vue 元件、掛載與外掛設定: `references/development-app/vue-development.md`
- 核心開發 `references/development-core/`
  - Package 開發與編輯: `references/development-core/package-development.md`
- 測試
  - Windwalker 測試工作: `references/test/testing.md`

## 驗證

- 重新查閱 instructions 與 references，確認 agent 已經使用了 Windwalker 專案開發相關的指令與文件。沒有漏掉或未遵循的部分。
