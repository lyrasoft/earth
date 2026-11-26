# Windwalker DI 與注入

## 設定位置

- 服務綁定放在 `etc/di.php`（獨立 DI 設定）的 `bindings`、`providers`、`aliases`、`extends`、`attributes` 等鍵。
- 在 Earth 專案中，DI 設定由 `etc/config.php` 的 `'di' => include __DIR__ . '/di.config.php'` 載入，且 `di` 區段最後處理。
- 路徑別名（`@temp`、`@logs`）定義於 `etc/define.php` 與 `etc/runtime.config.php`，由 `PathResolver` 解析。

## 取得服務

- 以類別名稱或別名從容器取得：`$this->container->get(...)`，或 `$this->app->service(...)`。
- 範例：

```php
$path = $this->container->get(PathResolver::class)->resolve('`temp/`cache');
$path = $this->app->service(PathResolver::class)->resolve('@migrations');
```

- `AppProvider` 以 `prepareSharedObject(PathResolver::class)` 註冊為 shared，每次取得皆為同一實例。

## 注入方式

- **建構子注入**：在建構子參數加上型別提示，容器自動解析。
- **屬性注入 `#[Inject]`**：可用於屬性與參數。定義位於 `vendor/windwalker/framework/packages/di/src/Attributes/Inject.php`。
  - `id`：服務 ID 或別名。
  - `forceNew`：建立新實例。
  - `tag`：字串或列舉值，用於解析帶標籤的服務。
- 解析順序：先 id 或第一個類別／介面型別，再找該 tag 的已註冊服務，最後才建立具體類別；介面不會自動建立。
- **`#[Autowire]`**：建立未解析的依賴，可用於類別、可呼叫物件與參數。
- **`#[NoAutowire]`**：略過單一參數的自動注入。
- **`#[Service]`**：解析共享服務，可用於屬性、參數與類別。
  - `shared: false`：非共享。
  - `tag`：帶標籤解析。
  - `providedIn`：限制服務所在的容器層級。
- 只有容器建立的物件會被注入；`new` 建立的物件不會。

```php
use Windwalker\DI\Attributes\Inject;

abstract class AbstractModule implements ModuleInterface
{
    #[Inject]
    protected AppContext $app;
}
```

## 以函式依標籤綁定服務

- 在 `bindings` 放一個接收 `?string $tag` 的 closure，依標籤回傳不同實作。
- 呼叫端以 `tag` 指定變體，預設不帶標籤時走 `default`。

```php
// etc/di.config.php，在回傳陣列內
'bindings' => [
    ChatModelInterface::class => function (
        Container $container,
        ?string $tag = null,
    ) {
        return match ($tag) {
            'claude' => $container->newInstance(ChatClaudeDriver::class),
            default => $container->newInstance(ChatGPTDriver::class),
        };
    },
],
```

```php
$gpt = $app->retrieve(ChatModelInterface::class);
$claude = $app->retrieve(ChatModelInterface::class, tag: 'claude');
```

- 以 closure 綁定時每次取得都會建立新物件；需要每個標籤共用一個實例時，改在 provider 以 `bindShared()` 綁定。
- 使用列舉作為標籤可避免拼字錯誤：參數型別改為 `?ChatModelDriver $tag`。若要同時接受字串，參數型別用 `string|\UnitEnum|null`，並以 rich enum 的 `wrap()` 轉換。
- 對不可信輸入，使用 `tryWrap()`（未知值回傳 `null`），而非 `wrap()`（未知值拋出 `ValueError`）。
- 標籤區分大小寫，`'SMS'` 與 `'sms'` 是不同標籤。

## Provider 生命週期

- Provider 實作 `register()`，可選擇實作 `bootDeferred()`、`bootBeforeRequest()`、`releaseAfterRequest()`。
- 多層容器與 `providedIn` 與 request／application 層級相關。

## 注意

- 類別若無法取得容器，請勿建立全域變數，應詢問如何傳入依賴。

## 參考連結

- [依賴注入概念](https://windwalker.io/documentation/framework/core-concepts/dependency-injection/dependency-injection.md)
- [服務變體](https://windwalker.io/documentation/framework/core-concepts/dependency-injection/service-variants.md)
- [DI 概覽](https://windwalker.io/documentation/components/di/index.md)
- [基本用法](https://windwalker.io/documentation/components/di/basic-usage.md)
- [屬性](https://windwalker.io/documentation/components/di/attributes.md)
- [參數](https://windwalker.io/documentation/components/di/parameters.md)
- [多層容器](https://windwalker.io/documentation/components/di/multi-levels.md)
