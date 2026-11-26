---
name: test
description: 共用的 Test 技能，可以從這裡入口尋找測試相關的指令、文件與技能。
---

# 技能：test

## 目的

當使用者要求執行測試或編寫測時，agent 可以使用這個技能來提供測試相關的指令、文件與技能。

由於專案可能擁有多重混和技術棧，測試相關的指令、文件與技能可能分散在不同的路徑下，這個技能提供一個統一的入口，讓 agent 可以快速找到測試相關的資源。

## 使用時機

- 編寫專案測試時
- 執行測試時
- 執行 code coverage 時
- 運用 code coverage 結果來找出缺少測試的程式碼
- 需要進行 E2E 整合測試時

## 範圍

本 Skill 主要提供不同技術棧的常用測試指令，個別專案如果有專屬的測試指令與流程，請優先參考專案內的 instructions。

## 測試相關知識與規範

- 所有測試工作共同的知識 `references/test-common.md`
- PHP / Windwalker 框架之測試 `references/windwalker-testing.md`
  - PhpUnit 測試規範與指引 `references/phpunit-testing.md`
  - App 測試（層級、自動重置、request 範圍、輔助 trait）`references/app-testing.md`
  - 資料庫測試（InMemory、真實 DBMS）`references/database-testing.md`
  - Coverage 規範與指引 `references/coverages.md`
  - Smoke Test / 暫存執行檔規範與指引 `references/smoke-test.md`
- E2e / Playwright 測試規範與指引 `references/e2e-testing.md`
  - Windwalker 登入流程（認證檔案、session cookie、session 橋接）`references/e2e-windwalker-auth.md`
  - 暫存前端頁面（無現有頁面渲染待測目標時）`references/frontend-scratch-page.md`

## 驗證

- 再一次檢視測試用的語法與 API 符合測試套件最新狀況
- 確認不必要測試的 simple getter/setter 沒有被測試，並已加上 `@codeCoverageIgnore`。
- 確認具有邏輯的 getter/setter 已被測試，或是沒有被誤標註 `@codeCoverageIgnore`。
