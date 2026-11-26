# PHP Lint (PHP-CS-Fixer)

## 前言

若是 Windwalker 專案，通常使用 `php-cs-fixer` 進行程式碼檢查與修正。

一般來說專案會預裝 `friendsofphp/php-cs-fixer`，並在根目錄有 `.php-cs-fixer.dist.php` 設定檔案。Composer 也會遇先準備好指令：

```json
"cs-check": "bash bin/php-cs-fixer check --diff",
"cs-fix": "bash bin/php-cs-fixer fix",
```

如果沒有這兩個指令，可以嘗試用 `vendor/bin/php-cs-fixer` 來執行。

LYRASOFT 使用 PER Code Style，詳細請見 `./php-cs.md`

## 執行方式

```bash
# 檢查程式碼
composer cs-check

# OR
php ./vendor/bin/php-cs-fixer check --diff

# -------

# 修正程式碼
composer cs-fix

# OR
php ./vendor/bin/php-cs-fixer fix
```

