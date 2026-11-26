# Design System 切版標準規範

所有專案編寫頁面之前，必須先完成 Design System 頁面（`/design-system`），並設為主選單第一項（無法放置時改放 Footer）。範例見公版：PHP（`entries/design-system.blade.php`）與 Vue（`resources/vue/DesignSystem.vue`）。

依 Figma Design System 調整元件樣式，並確認交件時符合以下項目：

- 使用 Bootstrap 5 SCSS 變數調整（見 [修改 Bootstrap 變數、自定義顏色](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345)）
- 不變動 Design System 版面
- 未修改的元件原封不動保留
- 專屬特殊元件建立在最下方的新區塊
- 保留 SCSS 檔案

## 版面與元件

```text
Bad: 把版面改得漂亮，並移除沒用到的元件
Good: 保留原版面，只修改設計稿有處理的元件
```

**為什麼？** 後續人員需要看出哪些元件已處理、哪些未處理，版面變動會讓這份判斷失效。

```text
Bad: 未修改的 BS 元件被刪除或改寫
Good: 未修改的 BS 元件原封不動留在原處；若壞掉，表示 SCSS 被改錯
```

**為什麼？** 網站可能會有第二期、第三期修改，移除元件會造成重工。

```text
Bad: 直接覆蓋 Bootstrap 元件樣式，或刪除原有 BS 元件
Good: 繼承並修改額外樣式（見上方連結），專屬元件另建新區塊
```

製作時若發現設計稿缺漏（例如 pagination 未設計），請向設計端提出；若該元件本來就不處理，讓它保持原樣。
