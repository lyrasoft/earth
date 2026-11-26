---
name: lyrasoft-best-practice
description: LYRASOFT 專案開發最佳實踐，涵蓋 Windwalker ORM、程式碼規範、樣式與審查的推薦寫法、Bad/OK/Good 範例與參考連結。
---

# Skill: lyrasoft-best-practice

## 目的

提供 LYRASOFT 專案共用的開發最佳實踐，讓撰寫或審查程式碼時能快速判斷推薦寫法，並連結到詳細文件。

## 使用時機

- 撰寫或修改 LYRASOFT 專案程式碼時，遇到不確定的寫法或想確認是否符合專案規範。
- 審查專案是否符合公司開發規範

## 範圍與指引

- Windwalker ORM 最佳實踐（查詢分組、Entity 型別化、N+1 查詢、重複資料防護）：@references/windwalker/orm-best-practices.md
- 程式碼規範（`references/cs/`）：
  - BEM 命名概念與規範：@references/cs/bem.md
  - CSS / SCSS 程式碼風格：@references/cs/css-cs.md
  - JS / TypeScript 程式碼風格：@references/cs/js-cs.md
  - PHP 程式碼風格：@references/cs/php-cs.md
  - SQL 程式碼風格與 Query Builder：@references/cs/sql-cs.md
- 結構與命名（`references/organizing/`）：
  - CSS / SCSS 結構與組織方式：@references/organizing/css-scss-organizing.md
  - PHP / Windwalker Service 組織方式：@references/organizing/php-service-organizing.md
  - 現代化 Vue 專案結構與語法規範：@references/organizing/vue-service-organizing.md
  - VueI18n 語系鍵名命名規則：@references/organizing/vuei18n-locale-key-naming.md
  - Windwalker 語系鍵命名規範：@references/organizing/windwalker-locale-key-naming.md
- 樣式（`references/styling/`）：
  - Bootstrap SCSS 客製化與變數覆蓋：@references/styling/bootstrap-scss.md
  - Bootstrap Dark Mode 最佳實踐：@references/styling/bootstrap-dark-mode.md
  - Design System 切版標準規範：@references/styling/design-system.md
- 審查（`references/review/`）：
  - PHP 程式碼審查指引：@references/review/review-php.md
- 刻意不涵蓋：框架安裝、部署設定與單一專案的私有規則

## 預期行為

- 先確認任務屬於哪個主題，再讀取對應的 reference。
- 以 Good 寫法為準，遇到 Bad 寫法時說明原因並提供修正方式。
- 詳細原理與完整說明依 reference 內的連結查閱，不在回覆中重複長篇內容。
- 讓結果保持簡潔、清楚，並符合專案慣例。

## 驗證

- 結果符合要求的任務與 repository 脈絡
- 沒有引入不必要或無關的變更
- 指示內容保持簡潔且可重用
