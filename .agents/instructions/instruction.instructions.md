# 編寫指引的指引

## 目的

當使用者要求將某些規則加入 `instruction`、`skills` 或 `docs` 時，這不表示要全部塞進 `instructions`。正確做法是依照規則的性質與使用頻率，將內容拆分到最適合的位置。

## 分類原則

當使用者要求「把規則加到 instruction / skills / docs」時，判斷方式依據:

- `instructions`：repository-level 規則與內容分流說明
- `skills`：共用技能與模板
- `docs`：長篇文件與範本

詳細介紹可參考 @../main.instructions.md#指引文件目錄說明

## 編輯原則

- 檔案連結:
  - 重要的檔案連結或指引，使用 `@相對路徑` 格式，如果要在清單中附加介紹文字，優先放後面 `- @link Text`，除非前方是祈使句。
  - 需要搭配標題時，可用 markdown 格式: `[顯示文字](相對路徑)` 連結
  - 一般敘述中提及檔案可依語境決定是否連結，單純提及檔名或檔案類型時使用反引號
  - 遠端網址連結使用 `<URL>` 格式，若要附上簡短說明文字，放在後面 `- <URL> Text`，除非前方是祈使句。
- 條列式說明，不需要太多前因後果 (除非該行為有特定情境或限制)
- 避免敏感資訊、產品機密、部署設定與金鑰

### Skills 編寫原則

請見公開規範文件:
- <https://www.skillsdirectory.com/docs/writing-effective-skills> Writing Effective Skills
- <https://www.skillsdirectory.com/docs/skill-md-format> Skill MD Format
- <https://www.skillsdirectory.com/docs/skill-file-structure> Skill File Structure

## 驗證

- 內容仍然是 markdown-only
- 指引仍保持通用且可重用
- 儲存的內容維持公司共用知識庫定位
- 專案不會逐漸演變成單一產品或專案的私有資料庫
