
# MVC 產生器

## 目的

用一個指令產生完整的後台 CRUD 頁面組：entity、migration、seeder、controller、列表（grid）檢視、編輯檢視與 route。可用來快速建立後台頁面，或重現自動產生頁面中的 bug。請產生一份可丟棄的副本，測試後再刪除。

## 使用時機

- 建立新的後台 CRUD 模組。
- 重現自動產生的列表／編輯頁面中的 bug。請使用不同名稱產生副本，測試後刪除。

## 建議流程與指令

建立 entity、migration、欄位定義與模組產生的完整流程，以及指令選項、產生的檔案結構與既有檔案處理，請見官方文件 [Code Generation](https://windwalker.io/documentation/unicorn/mvc-crud/code-generation.md)。

本技能的重點指令：

```shell
php windwalker g unicorn:mvc-admin <Stage>/<Name>
```

- `<Stage>` 通常為 `Admin` 或 `Front`；後台模組建議放在 `Admin/` 下並做好權限控管。
- `<Name>` 為 PascalCase 單數形式，例如 `Admin/Product`。
- 產生前，若選項或行為不確定，請先查閱官方文件（見下節），不要猜測。

若是要產生前台用畫面，或者是不含編輯功能的 simple 頁面，請使用:

```shell
php windwalker g unicorn:mvc-simple <Stage>/<Name>
```

## 先查閱官方文件

產生前，或選項與行為不清楚時，請先查閱 Windwalker 官方文件，不要猜測。可使用以下任一來源：

- MCP 工具 `windwalker-docs-search`：使用自然語言查詢，例如 `generator --pkg option`。
- `https://windwalker.io/llms.txt` ：文件頁面索引（每頁都有 `.md` 網址）；抓取相關頁面。

只有在文件無法回答時，才退而閱讀產生器程式碼（`vendor/windwalker/core/src/Core/Generator/`、`vendor/windwalker/unicorn/src/Generator/`）。

## 已知行為

- 產生器不會執行 migration 與 seeder，執行 `mig:go` 前請先確認是在開發資料庫上。
- 產生的 `.ts` 與 `.scss` 需經 `yarn build`（或 `yarn dev`）編譯，否則互動功能不會作用。搜尋欄位與後續步驟請見官方文件。
- 後台側邊選單不會自動產生。請手動在 `resources/menu/admin/sidemenu.menu.php` 新增項目，沿用既有項目的寫法：
  ```php
  $menu->link($lang('unicorn.title.grid', title: $lang('luna.<entity>.title')))
      ->to($nav->to('<snake>_list'))
  ```

## 除錯捷徑

簡單的 bug 可跳過 `g entity` 與 migration，直接執行 `unicorn:mvc-admin Admin/<Name>`。產生的 entity 只有空的屬性，若頁面需要欄位，請先定義資料表並執行 `build:entity`。

## 模板

若要改變產生內容，請編輯 `vendor/windwalker/unicorn/views/code/` 下的模板。不要為了修正產生器而直接編輯產生出來的檔案。

- `controller/`：controller class
- `model/entity/` 與 `model/repo/`：entity 與 repository
- `route-admin/` 與 `route-simple/`：route 檔（`routes/<kebab>.route.php`）
- `view/grid/`：列表頁面：grid 表單、列表檢視、`assets/`（scss/ts）、`views/`（列表、modal、工具列 blade）
- `view/edit/`：編輯頁面：編輯表單、編輯檢視、`assets/`、`views/`（編輯 blade、工具列 blade）
- `view/list/` 與 `view/item/`：替代的列表與項目頁面模板
- `workflow/`：workflow class

檔名本身也是模板，使用 `{% pascal($name) %}` 與 `{% kebab($name) %}` 佔位符。檔案副檔名為 `.tpl`。

Vendor 檔案會在 `composer install`／`update` 時被覆寫。若要保留變更，請將其複製到專案中，或在更新相依套件前記錄在專門的筆記中。

## 除錯流程（可丟棄的副本）

1. 使用不同名稱產生，例如 `Admin/DebugTest<Name>`。
2. 若頁面需要資料表，依建議流程定義 migration 並執行 `php windwalker mig:go`，然後重現 bug。
3. 完成後，刪除產生器建立的所有檔案：
   - `src/Entity/<Name>.php`、`src/Repository/<Name>Repository.php`
   - `resources/migrations/*_<Name>Init.php`
   - `resources/seeders/<kebab-name>.seeder.php`
   - `src/Module/Admin/<Name>/`（整個目錄）
   - `routes/admin/<kebab-name>.route.php`
   - 在 `resources/menu/admin/sidemenu.menu.php` 新增的選單項目
   - 若已執行 migration，請回滾並刪除資料表。
4. 以 `git status` 確認沒有殘留檔案。

## 驗證

- 清理後，`git status` 只顯示預期的檔案。
- 在清理前，產生的列表與編輯頁面可在瀏覽器中正常載入。

## 參考連結

- [程式碼產生（Code Generation）](https://windwalker.io/documentation/unicorn/mvc-crud/code-generation.md)
- [入門指南（Getting Started）](https://windwalker.io/documentation/unicorn/introduction/getting-started.md)
- [Code Generator 內建指令](https://windwalker.io/documentation/framework/console/commands/code-generator.md)
- [MVC Simple](https://windwalker.io/documentation/unicorn/mvc-crud/mvc-simple.md)
- [List Management（MVC Admin）](https://windwalker.io/documentation/unicorn/mvc-crud/mvc-admin/list-management.md)
