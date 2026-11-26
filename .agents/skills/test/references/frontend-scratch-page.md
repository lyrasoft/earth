# 暫存前端頁面

## 適用

需要前端測試，且**沒有現有頁面渲染待測目標**。

- 待測目標可能不是頁面（PHP class、模板、腳本、package 資產），需寄宿於某個頁面才能執行。
- 可能是特定元件、Fields、JS 工具、模板、Widget 等等。
- 若測試目標是 Field, components 等等可以獨立算繪的整合元件，不要手寫 HTML 去模擬，盡量直接載入該元件做測試。

## 測試前

- 閱讀指定檔案，確認它做什麼（繼承或匯出的內容）。
- 若為 PHP + JS 協作（PHP 註冊或輸出 JS 依賴的內容，或 JS 預期 PHP 先完成設定）：
  - 閱讀 Windwalker 文件，語意搜尋 `php js script pipeline`，可能有多個文件要綜合閱讀。
  - 查看 instructions ，決定適用的流程、建置步驟或初始化腳本。
  - 查看 @e2e-testing.md / @e2e-windwalker-auth.md 確認是否需要前端測試環境。
- 以 `grep` 找出使用者（class 名稱、tag 名稱或 import）。

## 選擇方式

- 已有真實頁面渲染目標：在該頁面測試。
- 沒有：使用暫存頁面。

## 暫存頁面

- 優先讓目標自行完成設定（例如 PHP 端渲染時註冊初始化腳本），以正常用法使用目標。
- 只有目標不會自行設定時，才從真實程式碼找出所需的初始化腳本、資產註冊或輔助函式；手寫 markup 會略過內建設定，使測試無效。
- 判斷行為前，先確認目標已啟用（元素已升級，或腳本已執行）。
- 設定如何運作、如何檢查與重建，閱讀 Windwalker 文件或 instructions 確認這件事的正確作法。

## 步驟

1. 產生 view 與 route：

   ```bash
   php windwalker g view <Stage>/<ViewName>
   php windwalker g route <stage>/<view>
   ```

2. 在產生的 route 檔案中填入 view class 名稱。
3. 在產生的空白檔案（可能是 blade 或 ts）放入測試用 HTML 與 JS，然後在瀏覽器開啟該 route（@e2e-testing.md）。
4. 測試完成後，刪除產生的 view、route 項目與檔案，以及殘留的暫存程式碼。不要把暫存頁面留在 repository 中。

## 相關文件

- [View 資產管理](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)
- [Package JavaScript](https://windwalker.io/documentation/framework/packages/package-javascript.md)
- [前端總覽](https://windwalker.io/documentation/framework/frontend/overview.md)
- [Code Generator（產生器）](https://windwalker.io/documentation/framework/console/commands/code-generator.md)
- [MVC View](https://windwalker.io/documentation/guides/mvc/view.md)
