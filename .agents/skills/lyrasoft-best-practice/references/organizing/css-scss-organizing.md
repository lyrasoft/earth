# CSS / SCSS Best Practice

- 使用 Bootstrap 5 作為基底，並用 `sass` 改寫變數後重新編譯，不引入原始 CSS 再大量覆蓋。
- 使用良好的 flex 結構編寫 HTML。

## 覆蓋 Bootstrap

```sass
// Bad: 直接覆蓋元件樣式，且色碼寫死
.btn-primary {
  background: #FF6666;
  border: 1px solid #FF6666;
  border-radius: 8px;
}

// Good: 在 _variables.scss 覆蓋 Bootstrap 變數
$primary: #355B81;
$btn-border-radius: 8px;

// Good: 變數無法調整、且確定全站共用的樣式才覆蓋
.btn {
  transition: ...;
}

// OK: 元件內少量覆蓋，沒有明顯錯誤，但仍建議優先改變數
.btn-primary {
  background: #355B81;
}
```

詳細說明：[修改 Bootstrap 變數、自定義顏色](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap)

## 主要入口

所有頁面統一載入 `main.scss`，順序為 variables → bootstrap → base → components → layout → pages。

```sass
// Bad: 沒有先載入變數，Bootstrap 無法套用自定義參數
@import "bootstrap/scss/bootstrap";
@import "variables";

// Bad: 直接引入編譯後的 Bootstrap CSS
@import "bootstrap/dist/css/bootstrap.min.css";

// Good: 變數 → Bootstrap → 專案樣式
@import "variables";
@import "bootstrap/scss/bootstrap";

@import "base/base";
@import "components/card";
@import "layout/header";
@import "pages/about";
```

編譯指令：

```
sass scss/main.scss css/main.css --load-path=node_modules
sass scss/main.scss css/main.css --load-path=node_modules --watch   # 監聽修改
```

使用 vitepress 請見 [Github 專案初始化](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/64192529/Github#%E4%BD%BF%E7%94%A8-Vitepress-%E9%80%B2%E8%A1%8C%E5%88%87%E7%89%88)。

## 目錄結構

- `base/`：專案基礎樣式
- `components/`：可重複使用的元件樣式
- `layout/`：版面樣式
- `pages/`：每個頁面的樣式

參考範例：<https://github.com/lyrasoft/arendelle-static/tree/staging/.vaseman/resources/assets/scss>

## 頁面包覆

每頁樣式用獨立的 wrapper 包覆，不要把 class 當成 BEM block 或直接套在整頁。

```sass
// Bad: 頁面 class 感染整頁，內部 BEM element 無法跨頁共用
.page-about {
  padding: 3rem;
  &__header {}
  &__title {}
}

// OK: 有包覆，但內部仍綁定頁面名稱，難以跨頁共用
.page-about-wrapper {
  .page-about {
    padding: 3rem;
    .page-about-title {}
  }
}

// Good: 包覆 scope 獨立，內部區塊命名不綁頁面，可跨頁共用
.page-about-wrapper {
  .page-about {
    padding: 3rem;
    background: white;

    .page-header {}
    .page-title {}
    .page-body {}
  }
}
```

- VitePress：不要把樣式寫在元件內的 `style`，統一放到 `scss/pages/`。

## 參考

- 標準切版包：<https://github.com/lyrasoft/outsource-layout-template/tree/main/resources/assets/scss>
- 真實範例：<https://github.com/lyrasoft/arendelle-static/tree/staging/.vaseman/resources/assets/scss>
- Design System 切版標準：<https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/58982402/Design+System>
- HTML / CSS 優良寫法與常見錯誤：<https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/950486/HTML+CSS>
- Vue / Vitepress 常見錯誤：<https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/114360341/Vue+Vitepress>
