# 語系鍵命名規範

> **必讀。** 新增、改名或重用任何翻譯鍵前（`resources/languages/**/*.ini`、PHP `trans()`、前端 `__()`），都必須先閱讀本文件。這是所有語系鍵的標準命名策略，不得自行發明其他結構。

## 扁平 INI 格式

Windwalker 語言檔是扁平的 `.ini` 檔，沒有巢狀結構。層級以點分段寫在同一個鍵中，值一律以雙引號包住。

```ini
flower.message.delete.confirm="Are you sure you want to delete?"
flower.field.created.by="Created By"
```

因為所有鍵都會被正規化（`A-Za-z0-9` 以外的字元轉為 `.` 並小寫），**單字間的分隔也是點**。`visibility_score`、`visibility-score` 與 `visibility.score` 是同一個鍵。多個單字請以點分隔（`flower.field.created.by`、`flower.prompt.list.action.add`）。不要依賴 `_`、`-` 或大小寫來區分兩個鍵。

同一個鍵可以同時是葉節點，又是其他鍵的前綴（`sakura.field.created` 與 `sakura.field.created.by`），這是 YAML 巢狀結構無法表達的。當某個詞彙有衍生鍵時，優先使用下文的 `.label` 形式。

相關鍵請相鄰排列，並依鍵名排序，讓譯者能一眼看出同一組。

## 命名空間與範圍

鍵以頂層、依領域劃分的命名空間開頭，例如 `app.*`、`ui.*`、`form.*`、`project.*`、`geo.*`。

在命名空間之下，區段依功能或頁面分組。每個命名空間與區段構成一個**範圍（scope）**，範圍內的鍵再依用途分組：`message.*`、`text.*`、`field.*`、`button.*`、`action.*`、`meta.*`。

```ini
; app scope
app.message.loading="Loading..."
app.message.error="An error occurred."
app.action.save="Save"
app.action.cancel="Cancel"

; geo.vistracker scope
geo.vistracker.message.loading="Loading visibility data..."
geo.vistracker.message.error="Failed to load visibility data."
geo.vistracker.field.visibility.score.label="Visibility Score"
geo.vistracker.field.visibility.score.desc="The overall visibility score for the selected keywords."

; geo.prompt.list scope
geo.prompt.list.message.loading="Loading prompts..."
geo.prompt.list.message.error="Failed to load prompts."
geo.prompt.list.action.add.prompt="Add Prompt"
```

任何層級都可以成為獨立範圍，但在用途區段之前，**最多使用三層**：

1. **命名空間**：最寬的範圍，依領域劃分。
2. **頁面 / 功能**：單一頁面或功能的中間範圍。
3. **共用版面**：被多個頁面重用、提供完整頁面功能的大型版面或元件。

## 共用詞彙（欄位、表頭、指標）

一個重要詞彙可能同時出現在圖表、表格、報表、圖示、按鈕、工具提示與說明中。請在命名空間的 `field.*` 下**只定義一次**，並到處重用。主要文字放在 `field.<term>.label`；說明或工具提示放在 `field.<term>.desc`。

不良做法（每個元件各一個鍵）：

```ini
geo.metric.sov="Share of Voice"
geo.metric.sov.desc="..."
geo.table.header.sov="Share of Voice"
geo.charts.tooltip.sov="Share of Voice"
geo.charts.tooltip.sov.desc="..."
geo.input.sov.label="Share of Voice"
```

良好做法：

```ini
geo.field.sov.label="Share of Voice"
geo.field.sov.desc="Description of Share of Voice..."
geo.field.sov.any.other.usage="..."

; shared UI controls may use a component-specific namespace
ui.table.header.toggle.all="Toggle All"
```

## 用途區段

依用途分組，而不是依元件分組。每個元件各自一個區段會讓層級過深、過於零碎。

| 區段 | 用於 |
|------|------|
| `meta.*` | 頁面標題、版面標頭、中繼資料、描述、副標題、麵包屑，以及其他頁面或版面層級的文字 |
| `message.*` | 警示、訊息、Toast、通知 |
| `text.*` | 註記、說明文字、提示、是／否，以及其他獨立文字 |
| `button.*` | 按鈕標籤；可包含多種狀態（文字、工具提示、載入中） |
| `action.*` | 常用動作標籤（儲存、取消、送出） |
| `field.*` | 表單欄位的標籤、說明、placeholder、驗證訊息 |

重用範例：

- 表格表頭、清單、卡片、工具提示重用 `text.*`、`field.*` 或 `message.*`。
- 常見控制項（全選、全部清除、全部重設）重用 `app.*` / `ui.*`（本套件為 `unicorn.core.*`）。
- 表單標籤、表格表頭、圖表標籤重用 `field.*`。
- 說明、警告、提示與狀態訊息使用 `message.*` 或 `text.*`；無處可歸的獨立文字放在 `text.*`。

## 衍生鍵

當一個主要名稱需要多個衍生字串時，將衍生後綴附加在主鍵之後。表單欄位通常需要標籤、說明、placeholder 與錯誤訊息：

```ini
project.settings.field.title.label="Project Title"
project.settings.field.title.description="The title of the project"
project.settings.field.title.placeholder="Enter project title"
project.settings.field.title.error.required="Project title is required"
project.settings.field.title.error.invalid="Project title is invalid"
```

共同前綴標示所屬群組，讓譯者能快速找到相關鍵。在同一範圍內請一致使用後綴 `label`、`desc`（或 `description`）、`placeholder`、`error.*`；同一用途不要混用 `desc` 與 `description`。

## 變體與縮寫

有長形式、縮寫或變體的詞彙，都放在同一個 `field.*` 鍵下：

```ini
geo.field.sov.label="Share of Voice"
geo.field.sov.abbr="SoV"
geo.field.aer.sov.label="AI Share of Voice"
geo.field.aer.sov.abbr="AI SoV"
geo.field.aer.sov.full="AI Engine Response Share of Voice"
```

## 依語意與元件情境命名

同一個英文單字可能代表不同意思。`Mentions` 在某張圖表中可能是提及次數，在另一處可能是被提及的次數、表單欄位，或清單中被提及的實體。`Cited` 可能是「已被引用」，也可能是「引用次數」。`Music` 這類名詞在英文沒有複數，但其他語言有，因此必須抽出複數鍵（`music.list`）。

在抽出名詞之前，先檢查元件與周邊程式碼：

- **表格表頭**：該欄儲存格（`td`）中顯示的資料。
- **圖表**：工具提示、圖例與座標軸的用途。
- **表單**：欄位預期的標籤、placeholder 與說明。
- **標題**：所呈現頁面、區段或 API 文件的單數／複數形式與情境。

不要因為英文文字相同就重用鍵；只有在**語意與情境**相符時才重用。有周邊註解或說明文字時，請依其指示。真正的複數形式請使用 `choice()`（參見 Windwalker Language 文件的 [複數規則](https://windwalker.io/documentation/components/language/plurals.md)）。
