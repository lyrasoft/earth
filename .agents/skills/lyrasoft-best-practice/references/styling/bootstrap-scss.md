# Bootstrap SCSS Best Practice

使用 Bootstrap 時，優先透過 SCSS 變數與 Sass 函式客製化，再重新編譯，而非覆蓋編譯後的 CSS。

詳細說明：[修改 Bootstrap 變數、自定義顏色](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap)

## 修改變數

變數需寫在 `@import "bootstrap/scss/..."` 之前；Bootstrap 的預設變數可在 `bootstrap/scss/_variables.scss` 搜尋。

```scss
// Bad: 變數寫在 Bootstrap import 之後，不會生效
@import "bootstrap/scss/variables";
$primary: #d96ed9;

// Good: 變數寫在 import 之前
$primary: #d96ed9;

@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";
@import "bootstrap/scss/variables-dark";
@import "bootstrap/scss/mixins";
@import "bootstrap/scss/maps";
```

## 新增主色與顏色變數

加入 `$theme-colors` 會自動產生 `text-*`、`bg-*`、`btn-*` class；合併 `$colors` 則輸出為 CSS 變數（如 `--bs-golden`）。

```scss
// Bad: 寫死色碼，沒有對應的 Bootstrap class 或 CSS 變數
.text-facebook { color: #4267B2; }

// OK: 只加入 theme-colors，無法使用 rgba 工具 class
$facebook: #4267B2;
$theme-colors: map-merge($theme-colors, ("facebook": $facebook));

// Good: 加入 theme-colors 並補齊 rgb 與工具 class
$facebook: #4267B2;
$custom-theme-colors: ("facebook": $facebook);

$theme-colors: map-merge($theme-colors, $custom-theme-colors);
$theme-colors-rgb: map-loop($theme-colors, to-rgb, "$value");
$utilities-colors: map-merge($theme-colors-rgb, $utilities-colors);
$utilities-text-colors: map-loop($utilities-colors, rgba-css-var, "$key", "text");
$utilities-bg-colors: map-loop($utilities-colors, rgba-css-var, "$key", "bg");

// Good: 合併 $colors 產生 CSS 變數
$colors: map-merge($colors, ("golden": #D4A017));
```

## 調整元件參數

```scss
// Bad: 在元件內寫死圓角，之後難以統一調整
.card { border-radius: 15px; }
.btn { border-radius: 5px; }

// Good: 透過變數統一調整
$link-decoration: none;
$card-border-radius: 15px;
$btn-border-radius: 5px;
$input-border-width: 0;

@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";
```

## 自訂專案變數

```scss
// Bad: 同一字型重複寫死在各處
.card-title { font-family: 'Roboto', 'PingFang TC', sans-serif; }

// Good: 定義變數後統一使用
$title-font: 'Roboto', 'PingFang TC', 'Heiti TC', '微軟正黑體', sans-serif;

.card {
  .card-title {
    font-family: $title-font;
    color: $primary;
  }
}
```

## 善用 Sass 與 Bootstrap 函式

```scss
// Bad: 手動寫死 hover 色碼，與主色脫鉤
a:hover { color: #b35a5a; }

// Good: 用 darken() 從變數推導
a {
  color: $primary;

  &:hover {
    color: darken($primary, 5%);
  }
}

// Good: 使用 button-variant mixin 快速產生按鈕樣式
.btn-custom {
  @include button-variant(#581493);
}
```

參考：[Sass 函式](https://sass-lang.com/documentation/at-rules/function)

## 修改 class 樣式

變數無法調整、且全站共用的樣式才覆蓋內建 class；區塊特殊樣式請使用獨立 class。

```scss
// Bad: 在頁面內覆蓋 Bootstrap class，影響範圍不明確
.about-page {
  .btn {
    transition: ...;
  }
}

// Good: 全站共用的樣式，覆蓋內建 class
.btn {
  transition: ...;
}

// Good: 區塊特殊樣式，使用獨立 class
.about-page {
  .btn--more {
    transition: ...;
  }
}
```

## 自訂實用 class

擴充 spacers 或用 `$utilities` 新增 class 時，需合併既有值，不要覆寫。

```scss
// Bad: 直接覆寫，會丟失 Bootstrap 預設的 utilities
$utilities: (
  "ft": (property: font-size, class: ft, values: (58: 3.6rem, 64: 4rem))
);

// Good: 合併既有 map
$spacers: map-merge((6: ($spacer * 6), 7: ($spacer * 12)), $spacers);

$utilities: map-merge(
  $utilities,
  (
    "ft": (property: font-size, class: ft, values: (58: 3.6rem, 64: 4rem)),
  )
);
```

## 善用 CSS 變數

```scss
// Good: 直接使用 CSS 變數
.foo { color: var(--bs-primary); }

// Bad: rgba() 不接受整組顏色變數
.foo { background-color: rgba(var(--bs-primary), .5); }

// Good: 使用 -rgb 版本做半透明
.foo { background-color: rgba(var(--bs-primary-rgb), .5); }
```

自訂 CSS 變數：

```scss
$special-color: #D18B3B;

:root {
  --my-color: #f5f5f5;
  --banner-bg: #{$primary};
  --special-color: #{$special-color};
  --special-color-rgb: #{to-rgb($special-color)};
}
```

## 常見問題

### 按鈕文字變黑色

設計主色較淺時，Bootstrap 會自動把文字轉為黑色。可在 `_variables.scss` 於 Bootstrap import 前降低對比門檻（常用 2.7–3 之間）：

```scss
$min-contrast-ratio: 2.8;
```