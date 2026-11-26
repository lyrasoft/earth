# Agent 主要指引入口

## 目的

這個檔案是專案內的 Agent 指引，提供協作者與 AI agent 在專案內的操作規範與限制。

## 專案介紹

請見 @../AGENTS.md

## 專案結構

請見 @instructions/architecture.instructions.md

## 指引文件目錄說明

- @README.md：專案總覽與共用標準
- @AGENTS.md：協作者與 AI agent 的基本指引
- `instructions`：repository-level 規則與內容分流說明
  - 盡可能簡短，條列式，指向任務內容到合適的文件
  - 可能根據不同任務與適用的檔案路徑切割，並用 `applyTo:` front-matter 指定適用範圍
  - 可能指向遠端文件或 MCP tools，盡可能再開始工作前搜尋並讀取任務相關的內容
  - @./instructions/README.md：指引總覽與分段入口
- `skills`：共用技能與模板
  - 可拆裝組合的技能，包含多步驟的工作流程、範例、腳本與資源
  - 可隨時根據需要從遠端安裝或複製貼上，用以啟動某一種能力
  - 可能根據不同的任務類型適用不同的 skills
  - @./skills/index.md：技能總覽與分段入口
  - @./skills/<name>/references/：技能相關的參考資料
- `docs`：長篇文件與範本
  - 僅在需要時載入，可能是框架與函式庫說明文件、專案知識背景、操作流程 SOP 等等。
  - @./docs/index.md：文件總覽與分段入口
- `prompts`：AI 工作流程 prompts

### docs 段落與長文件載入規則

`docs` 可能會包含較長的說明文件，請依「需要時才載入」的原則處理，不要一開始就一次讀完整個資料夾：

- @docs/index.md：文件總覽與分段入口，先讀這個即可快速判斷需要看哪個文件。
- `docs/templates/`：文件模板、文件架構與寫作方式，適合創建或修正文件時載入。
- 新增 docs 類別時，請同步補充此處的段落說明，讓後續使用者知道該文件適合在哪個情境下載入。
- 若不確定要看哪個 docs，先讀 @docs/index.md，再依主題定位到需要的長文件。

這些段落需保持簡短、可擴充，方便未來新增新的 docs 分類而不必重寫整份指引。
