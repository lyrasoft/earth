# Bootstrap Dark Mode 最佳實踐

Bootstrap 5.3.0 之後提供深色模式。請善用深色模式，不要手動把白色模式的背景與文字顏色逐一改成深色。

詳細說明：[Bootstrap 5.3 Color modes](https://getbootstrap.com/docs/5.3/customize/color-modes)

## 版本需求

深色模式需要 Bootstrap 5.3.0 以後的版本。

```json
// Good
"dependencies": {
  "bootstrap": "^5.3"
}
```

## 啟動 Dark Mode

```html
<!-- Good: 在 html 加上 data-bs-theme，全站切換為深色 -->
<html data-bs-theme="dark">

<!-- Good: 在任意區域加上 data-bs-theme，只讓該區域變為淺色或深色 -->
<div data-bs-theme="dark">...</div>
```

## 按鈕文字顏色

若深色模式下按鈕的反白文字變黑，請依[修改 Bootstrap 變數、自定義顏色](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap#%E6%8C%89%E9%88%95%E6%96%87%E5%AD%97%E8%AE%8A%E9%BB%91%E8%89%B2)處理。

## 單區域反白

設計白底網站時，Footer 等區域需要黑底白字。

```html
<!-- Bad: 連結會變成死白或無法跟隨主題，調整困難 -->
<div class="l-footer bg-dark text-light">...</div>

<!-- Good: 使用 text-bg-* 自動搭配對比文字顏色 -->
<div class="l-footer text-bg-dark">...</div>

<!-- OK: 使用 data-bs-theme 與 text-body，整區切換為深色 -->
<div class="text-body" data-bs-theme="dark">...</div>
```

**說明：**

- `text-bg-*` 會在更換背景色時一併設定對應的文字顏色，適用於 badge、card 等元件。
- 使用 `data-bs-theme` 的方式，要求 `_variables.scss` 內不能停用 dark mode。

```html
<!-- Bad: 依顏色用 if/else 判斷文字顏色 -->
<span v-if="themeColor === 'warning' || themeColor === 'info'"
  :class="`badge bg-${ themeColor } text-dark`">{{ themeColor }}</span>
<span v-else :class="`badge bg-${ themeColor }`">{{ themeColor }}</span>

<!-- Good: 使用 text-bg-* 一行解決 -->
<span class="badge" :class="`text-bg-${ themeColor }`">{{ themeColor }}</span>
```

參考：[Color background](https://getbootstrap.com/docs/5.3/helpers/color-background/)

## 連結變更顏色

```html
<!-- Bad: text-primary 會失去 hover 變色效果 -->
<a class="text-primary" href="#">Link</a>

<!-- Good: link-primary 保留 hover 特效 -->
<a class="link-primary" href="#">Link</a>
```

參考：[Colored links](https://getbootstrap.com/docs/5.3/helpers/colored-links/)
