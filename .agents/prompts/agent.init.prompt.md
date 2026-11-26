# 提示：初始化儲存庫指引

在為儲存庫建立基本的 AI 與貢獻者指引時使用此提示。

檢視儲存庫，確認其實際用途、結構與文件模式。建立或更新 AGENTS.md 及相關的儲存庫指引，讓未來的貢獻者能快速了解專案。

- 建立或調整 `.github/copilot-instructions.md`，加上指向 @../main.instructions.md 的連結，並提供專案的簡短說明。
- 根目錄的 `AGENTS.md` 也連結到 @../main.instructions.md，並提供專案的簡短說明。
- @../instructions/architecture.instructions.md 是專案結構說明，預設是空的，請閱讀並分析本專案的結構，並在此檔案中提供說明。
  - 如果專案規模很大，請將詳細的結構說明放到 `docs/architecture.md` 內，此檔案不需精簡條列，可詳細說明專案的目錄結構、主要模組、核心功能與相依性。
- 根據本專案的實際情況，建立或更新 instructions 資料夾內的其他指引文件，並在 @../main.instructions.md 中提供連結。
