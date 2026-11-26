# 技能索引

這個目錄保存可重用的跨專案技能與操作指引，能直接套用於不同倉庫與工作場景中。

## 範圍

- 倉庫設定指引
- 可重用的任務模式
- 團隊操作 checklist
- AI 輔助工作的指示模板

## 檔案命名規範

這個資料夾中的內容應依類型使用不同檔名：

- [總覽說明](../../../README.md)
- `SKILL.md`: 單一技能的正式定義
- `*.prompt.md`: 可重用 prompt 模板
- [repo-level 指引](../../../AGENTS.md) / `.agents/main.instructions.md`

也就是說，若某份內容是技能本身，請命名為 `SKILL.md`，不要用 `README.md` 替代；`README.md` 只保留給總覽與索引。

## 組織

- [LYRASOFT 最佳實踐](lyrasoft-best-practice/SKILL.md)
- [Windwalker](windwalker/SKILL.md)
- [test](test/SKILL.md)：新增、更新與執行 PHPUnit 測試的流程
- [templates](templates/)：共用的技能與指引起始模板

## 維護

此資料夾應保持聚焦於廣泛適用的公司共用知識，避免存放單一產品或專案的私有內容，除非它明確被設計為可重用模板。
