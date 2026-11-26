# VueI18n 語系鍵名命名規則

## 命名空間與範圍（Scope）

翻譯鍵依頂層、以領域劃分的命名空間組織，例如 `app.*`、`ui.*`、`form.*` 與 `project.*`。
在每個命名空間內，依功能或頁面再以區段（section）分組。

每個命名空間與區段構成一個範圍（scope），範圍內的鍵依用途分組，例如 `message.*`、`text.*`、`field.*` 與 `button.*`。
例如，`geo.*` 命名空間涵蓋平台的 GEO 功能，其區段包括 `geo.visibility.*`、`geo.prompt_list.*` 與 `geo.compare.*`。

範例：

```yaml
app:
    # app scope
    message:
        loading: "Loading..."
        error: "An error occurred."
    action:
        save: "Save"
        cancel: "Cancel"
geo:
    # geo scope
    vistracker:
        # geo.vistracker scope
        message:
            loading: "Loading visibility data..."
            error: "Failed to load visibility data."
        field:
            visibility_score:
                label: "Visibility Score"
                desc: "The overall visibility score for the selected keywords."
    prompt_list:
        # geo.prompt_list scope
        message:
            loading: "Loading prompts..."
            error: "Failed to load prompts."
        action:
            add_prompt: "Add Prompt"
```

任一層級都可以形成自己的範圍，但建議不超過三層：

1. 命名空間：最廣的範圍，依領域組織。
2. 頁面／功能：頁面或功能的中間範圍。
3. 共用版面（Shared Layout）：可被多個頁面重用的大型版面或元件範圍；這類元件通常提供完整的頁面級功能。

每個範圍都可以定義自己的 `message.*`、`text.*`、`field.*`、`button.*` 等鍵，供該範圍內使用。

## 共用術語命名（欄位、表頭、指標等）

每個命名空間中的重要術語可能出現在圖表、表格、報表、圖示、按鈕、提示與說明中。
請在該命名空間下使用共用的 `field.*` 鍵，而不是為每個元件各建一組鍵。
欄位的主要文字放在 `field.*.label`，說明或提示文字放在 `field.*.desc`。
跨元件重用同一鍵，可保持用語一致並方便維護。

不良範例：

```yaml
geo:
    metric:
        sov: "Share of Voice"
        sov_desc: "..."
    table:
        header:
            sov: "Share of Voice"
    charts:
        tooltip:
            sov: "Share of Voice"
            sov_desc: "..."
    input:
        sov:
            label: "Share of Voice"
```

良好範例：

```yaml
geo:
    field:
        sov:
            label: "Share of Voice"
            desc: "Description of Share of Voice..."
            any_other_usage: "..."

ui:
    table:
        # 共用的表格 UI 控制項可使用元件專屬的命名空間。
        header:
            toggle_all: "Toggle All"
```

如此可將跨多個情境使用的術語集中在同一個 `field.*` 鍵下。

## 依用途分組 UI 元件與共用字串

除了共用術語與欄位，請將鍵依用途歸入共用區段，讓譯者能理解每個字串的用法與脈絡。

這些區段包括但不限於：

- `meta.*`：頁面標題、版面標題、metadata、描述、副標題、麵包屑等頁面級或版面級文字
- `message.*`：提示框、訊息、toast 與其他通知
- `text.*`：註解、說明文字、提示、是／否，以及其他獨立文字
- `button.*`：常用按鈕動作標籤，可包含多種互動狀態（文字、tooltip、載入中）
- `action.*`：常用動作標籤（save、cancel、submit 等）
- `field.*`：表單欄位的標籤、描述、placeholder 與驗證訊息

請依用途分組，而非為每個元件建立獨立區段；否則翻譯階層會過深且零散。

重用鍵的實際範例：

- 頁面元件中的表頭、列表、卡片與 tooltip 文字，通常可重用 `text.*`、`field.*` 或 `message.*` 下的鍵。
- 全選、取消全選、清除全部、重設全部等常見控制項，可重用 `app.*` 或 `ui.*` 下的鍵。
- 表單標籤、表頭與圖表標籤通常可重用 `field.*` 下的術語。
- 頁面上的說明、警告、提示與狀態訊息可使用 `message.*` 或 `text.*`。不屬於其他類別的獨立文字通常放在 `text.*`。

### 衍生鍵

當 UI 元件或功能需要從一個主要名稱／鍵衍生出多個不同名稱時，請將衍生後綴放在主鍵之後。

最常見的情況是表單。例如欄位 `project.settings.field.title` 可能需要標籤、描述、placeholder、錯誤訊息等，都從主鍵衍生，例如：

```yaml
project:
    settings:
        field:
            title:
                label: "Project Title"
                description: "The title of the project"
                placeholder: "Enter project title"
                error:
                    required: "Project title is required"
                    invalid: "Project title is invalid"
```

這種做法的好處是共同的前綴或後綴讓鍵容易辨識為同一組，翻譯時也能快速找到相關的鍵。

### 變體與縮寫

術語可能數量多、名稱長，或有多種變體與縮寫。存放於 `field.*` 時，請如上例將這些形式放在同一個鍵下，方便譯者理解與維護。

```yaml
geo:
    field:
        sov:
            label: "Share of Voice"
            abbr: "SoV"
        aer_sov:
            label: "AI Share of Voice"
            abbr: "AI SoV"
            full: "AI Engine Response Share of Voice"
```

### 依語意與元件脈絡命名

英文等語言中，同一個字可能在不同情境下有不同意義。例如 `Mentions` 在一張圖表中可能指「被提及次數」，在另一張圖表中可能指「被提及的次數」，在表單中可能是「提及」，在列表或查詢歷史中可能是「被提及的實體」。

`Cited` 可能表示「已被引用」，也可能表示「被引用次數」。

`Music` 清單在英文中沒有複數形式，但其他語言可能有，因此複數鍵（`music_list`）需要獨立提取以利翻譯。

因此，當你在元件中看到需要提取翻譯的名詞時，請先檢查元件的脈絡與周圍程式碼，例如：

- 表頭：請檢查該欄位在 cell 或 td 中顯示的資料。
- 圖表：請檢查圖表 tooltip、legend 與 axis 的用途。
- 表單：請檢查此欄位 label、placeholder 與描述所預期的回應內容。
- 標題：請檢查此頁面的單複數形式與應用脈絡，或該區段呈現的內容、取得的 API 文件。

這是為了避免同名鍵被誤用。若周圍有註解或說明文字，請一併參考。

### 列舉值（Enum）

由於所有列舉都是全域共用，請放在 `app.enum.*` 下。同樣地，每個 case 的鍵應放在 `title`、`desc`、`short`、`full` 等子鍵下，與 `field.*` 相同。

```yaml
app:
    enum:
        sentiment:
            positive:
                title: "Positive"
                desc: "The sentiment is positive."
            negative:
                title: "Negative"
                desc: "The sentiment is negative."
            neutral:
                title: "Neutral"
                desc: "The sentiment is neutral."
```

## 既有命名空間

- `app.*`：共用 UI 字串與全域共用的列舉值。**一律優先使用 `app.*` 鍵，不要在功能命名空間中重複定義常用字串。**
- `form.*`：表單欄位提示、驗證訊息、multiselect 屬性、檔案上傳器字串
- `ui.*`：一般 UI 框架元素：分頁、導覽側邊欄、表格控制項、語言設定
- `project.*`：專案列表、建立精靈、總覽、設定、分享、日期範圍；總覽頁字串使用 `project.overview.*`
- `onboarding.*`：設定步驟、專案預覽與試用結帳；共用的步驟動作使用 `onboarding.button.*`
- `auth.*`：驗證／登入
- `bill.*`：帳務與訂閱領域。`bill.usage.*`、`bill.addon.*`、`bill.currency.*`、`bill.field.*` 為共用的用量、配額與幣別術語；`bill.billing.*` 為帳務／發票歷史頁面範圍（收據表格、下載／預覽）；`bill.plan.*` 為方案總覽頁面範圍（目前方案卡、定價卡、追蹤單位附加項目、方案變更／取消對話框）
- `seo.*`：排名追蹤與關鍵字
- `geo.*`：GEO、AI 能見度與提示詞
- `unified.*`：共用的 SEO／GEO 術語；跨通路與 SERP 指標使用 `unified.field.*`，含 `label`／`abbr`／`desc` 變體。AI 專屬指標仍放在 `geo.field.*`。

新增鍵或功能命名空間時，請同時更新兩份語系 YAML 檔；不需修改 Nuxt 設定。新增語言時，建立 `app/i18n/locales/{locale}.yaml`，保持相同的鍵結構，並在 `nuxt.config.ts → i18n.locales[]` 中註冊。

### `app.*` 鍵

`app.*` 包含以下子命名空間：

- `app.action.*`：常用按鈕標籤（save、save_settings、submit、cancel、add、edit、update、close、back、next、previous、search、clear、reset、refresh、view、import、export、upload、download、back_to_top）
- `app.status.*`：狀態與回饋訊息（loading、saving、processing、success、error、done、saved、deleted、no_data、coming_soon、beta、not_available）
- `app.confirm.*`：確認對話框文字（unsaved_changes、leave、leave_desc）
- `app.enum.*`：全域共用的列舉 case 翻譯，含 `title`／`desc` 變體
- `app.footer.*`：頁尾連結與版權

### `auth.*` 鍵

`auth.login.meta.title`、`auth.register.meta.title` 與 `auth.logout.meta.title` 為頁面標題。
登入、登出與票券進度文字歸入其 `message.*` 群組。
使用者選單的導覽與動作保留既有的 `auth.user_menu.*` 群組。

### `form.*` 鍵

`form.*` 涵蓋所有表單與輸入相關的 UI 字串：

- `form.field.*`：共用的欄位 placeholder 與驗證訊息（如 `url.error.invalid`、`error.required`、`select.placeholder`）。
- `form.text.*`：共用的必填／選填標籤與提示。
- `form.multiselect.*`：共用的控制項字串，分組於 `button.*`、`field.*`、`message.*` 與 `text.*` 下。
- `form.uploader.*`：檔案上傳器的動作、狀態訊息與提示，分組於 `button.*`、`message.*` 與 `text.*` 下。

### `ui.*` 鍵

`ui.*` 涵蓋不屬於特定功能的一般 UI 框架元素：

- `ui.pagination.*`：`prev`、`next`、`page`、`of`
- `ui.nav.*`：側邊導覽標籤：`my_keywords`、`my_projects`、`billing`、`guide`、`create_project`
- `ui.table.*`：表格控制項：`per_page`
- `ui.settings.*`：語言設定面板：`language_title`、`language_label`
