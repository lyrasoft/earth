# Windwalker Package 開發基礎

Windwalker Package 是一個 Composer 套件（`type` 為 `windwalker-package`），可把 MVC 類別、Entity、路由、Migration、設定檔等可重用部分安裝進宿主應用程式。

完整說明請參考官方文件：https://windwalker.io/documentation/framework/packages/package-development.md

## 基本結構

```
acme/portfolio
├── composer.json
├── README.md
├── resources/          # pkg:install 會複製的檔案
│   ├── config/
│   ├── routes/
│   ├── migrations/
│   └── languages/
└── src/
    └── PortfolioPackage.php
```

## composer.json

- `type` 設為 `windwalker-package`。
- 在 `autoload.psr-4` 宣告命名空間。
- 在 `extra.windwalker.packages` 註冊 Package 類別。

```json
{
    "name": "acme/portfolio",
    "type": "windwalker-package",
    "autoload": {
        "psr-4": { "Acme\\Portfolio\\": "src/" }
    },
    "extra": {
        "windwalker": {
            "packages": ["Acme\\Portfolio\\PortfolioPackage"]
        }
    }
}
```

## Package 類別

- 繼承 `Windwalker\Core\Package\AbstractPackage`。
- 實作 `install(PackageInstaller $installer): void`，呼叫安裝方法複製資源。
- 資源路徑使用 `static::path()` 建立。

```php
class PortfolioPackage extends AbstractPackage
{
    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('resources/config/*.php'), 'config');
        $installer->installRoutes(static::path('resources/routes/*.php'), 'routes');
    }
}
```

常用安裝方法包括 `installConfig()`、`installRoutes()`、`installMigrations()`、`installLanguages()`、`installViews()`、`installSeeders()`、`installFiles()`、`installModules()`、`installMVCModules()`。每個方法都可傳入 tag，讓使用者只安裝部分資源。各方法的參數與細節請見官方文件。

## 在宿主應用程式中研發

1. 宿主應用程式的 `composer.json` 加入 `path` repository 指向本地 Package 資料夾，並 `require` 該套件。
2. 執行 `composer update acme/portfolio`。
3. 執行 `php windwalker pkg:install acme/portfolio --all` 安裝資源。
4. 修改會被 `pkg:install` 複製的檔案後，需要重新安裝。加上 `--force` 會覆寫既有檔案，執行前請先 commit 宿主應用程式。

### 新專案在 Host App 啟動時

使用者提出首次開發指令時，先詢問使用者**要建立的套件名稱與命名空間**，再詢問希望放在外部目錄還是 `vendor/` 內。

若是希望放在 `vendor/` 內，可以先放到專案的 `vendor/{namespace}/{name}` 內，若是希望放在外部目錄，則依照使用者指定的目錄先存放。

接著使用 `composer.json` 加入 `path` repository 指向該資料夾，然後 require 套件名稱。

## 注意事項

- 設定檔不要寫入明文密鑰或密碼，請用 `env()` 讀取。
- `installModules()` 會以文字方式讀寫檔案，二進位檔（圖片、字型）請使用 `installFiles()`。
- 開發初期就 `git init` 並定期 commit，方便回復失敗的變更。

## 相關文件

- 設定檔與 `ConfigModule`：<https://windwalker.io/documentation/framework/getting-started/configuration.md>
- Package Migration：<https://windwalker.io/documentation/framework/packages/package-migration.md>
- Package JavaScript：<https://windwalker.io/documentation/framework/packages/package-javascript.md>
- 修改既有 Package 或 Framework 原始碼（`composer install --prefer-source`）：參考官方文件「Modifying an Existing Package」段落。
