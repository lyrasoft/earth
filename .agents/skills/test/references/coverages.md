---
applyTo: "**/{test,Test,tests,Tests}/**/*.php"
---

# Coverage 測試與規範

尋找並補齊 coverage 缺口的流程，請見 [test 技能](../SKILL.md)。

## 使用 PhpUnit 產生 coverage 報告

- 預設使用 phpunit + Xdebug 產生 coverage 報告，並輸出到 `tmp/coverage`。請確保已安裝 Xdebug，並在 `php.ini` 中啟用。
- 若沒有 Xdebug，請使用 `--coverage-php` 產生 coverage 資料檔，並使用 `phpdbg` 或其他工具產生報告。

## 覆蓋與否的判斷原則

### 無邏輯的 getter/setter 或單純代理方法不需獨立測試，並排除於 code coverage

不要只為了執行純 getter/setter（僅回傳或指派屬性，可選擇 `return $this`）而撰寫獨立測試。若此類方法無法透過其他行為測試
取得有意義的覆蓋，請在方法 docblock 加上 `@codeCoverageIgnore` 排除。之後若加入邏輯（驗證、正規化、延遲初始化、副作用等），
**必須**移除該忽略標籤，並以測試覆蓋其行為。

```php
/**
 * @codeCoverageIgnore
 */
public function setOptions(array $options): static
{
    $this->options = $options;

    return $this;
}
```

只呼叫其他方法或服務、沒有額外行為的單純代理方法也可排除。但若參數或回傳值經過轉換、驗證或其他處理，就必須以測試覆蓋其行為。

```php
/**
 * @codeCoverageIgnore
 */
public function createAnyInstance(array $options): static
{
    return $this->builder->createAnyInstance($options);
}
```

### 含邏輯或會拋出錯誤的分支不得忽略

`@codeCoverageIgnore` 僅適用於無邏輯的 getter/setter 與單純代理方法。任何包含邏輯（條件、轉換、fallback、副作用）或會拋出例外的分支，
即使只有幾行，**都必須**測試。不可為了提高 coverage 而忽略這類分支。只有真正無法執行的程式碼（例如無法取得的外部 driver，
或程序層級的 `exit`／signal 處理）才可忽略，並須在標籤中註明原因。

純防禦性或 fallback 分支，若已證明無法透過公開行為到達（例如呼叫端已保證該條件），也可忽略。請以 `@codeCoverageIgnoreStart` / `End`
包住（或標記整個方法），並以註解說明無法到達的原因。請先驗證該說法；若實際可到達，就必須測試。
