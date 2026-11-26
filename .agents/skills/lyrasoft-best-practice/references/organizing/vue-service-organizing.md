# 現代化 Vue 專案結構與語法規範

## 寫法規範

- Service / Controller：用 composable（`useXxx()`），不用 class
- Function：優先 `function` 宣告（會被提升，可放在呼叫之後）；composable 內同樣優先使用
- 允許箭頭函式：單行且數量多、包裹其他函式（如 `useDebounceFn`）、需宣告型別；必須先宣告才能呼叫
- 禁止：`const foo = function () {}`（變數 + 匿名 function）
- Composable 一律具名匯出，不用 `default`
- 在 `useXxx()` 最外層初始化 hooks（如 `useRouter()`），內部 async 函式直接使用，不在 async 函式內呼叫

## 共用 composable

- 每頁獨立狀態：一般 `useXxx(options)`，每次呼叫建立新 ref（如 `useListFetcher`）
- 跨頁面共用狀態：`createGlobalState()`（[vueuse](https://vueuse.org/shared/createGlobalState/)），只初始化一次

## 檔案命名與用途

| 類型 | 命名 | 用途 |
| --- | --- | --- |
| Composable | `useFoo.ts`、`onRouteUpdated.ts`、`createApiClient.ts` | 元件內獨立呼叫的功能封裝，離開即失效；可透過 store 的全域變數互動；動詞開頭、camelCase；同實體可合併一檔，無關者不合併 |
| Store | `*.store.ts`（如 `auth.store.ts`） | 全域變數，只放 getter / setter 與資料轉換，不含業務邏輯（register / login / create / delete、呼叫遠端 API 屬於 controller / service） |
| Service | `useFlowerService.ts`、`flower.service.ts` | 商業邏輯、API 呼叫、單一項目 CRUD，會被多處重用 |
| Controller | `useFlowerController.ts`、`flower.controller.ts` | 頁面跳轉、呼叫 API 前的資料整理，可組合多個 service；API 回應後透過 store setter 寫入；中小型專案可省略 Service |
| 散裝函式 | `flower.ts`、`unit.converters.ts`、`currency.formatters.ts` | 無狀態、無 storage 的純函式集合；小寫 kebab-case 或複數名詞 |
| 過渡用 class | `main.controller.ts`、`user.service.ts` | 仍為 class 的檔案，未來不應再新增 |

- 無狀態、`use` 開頭的簡單函式（如 `useScrollToTop(root)`）可放散裝函式檔案
- 回傳 ref / methods 等有狀態內容者，必須放 composable
- 全域核心變數的 store 包裝器即使用 composable 寫法（`createGlobalState`），仍命名為 `store`（如 `useAuthStore`）

## Service 角色後綴（`use` 開頭）

- 每種功能各自一個 composable，不混用編輯、版面、格式化
- `useXxxStore`：底層全域共用變數
- `useXxxEditor`：編輯狀態（`editingItem`、`edit()`、`cancel()`、`save()`），常跨頁共享
- `useXxxPresenter` 或純名詞（如 `useBreadcrumb`）：視覺與版面狀態，類似 Windwalker 的 ViewModel 或 `XxxViewService`；通常以 `createGlobalState` 包裝
- `useXxxController`：對照 API 入口，處理需共用的 API 呼叫

## 目錄結構（DDD）

- 頂層 `src/`：`core`、`features`、`shared`，其下再依 domain 分子目錄
- `core/`：全站共用或初始化；缺少時全站打不開（如 `core/main/`、`core/api/`、`core/events/`）
- `features/`：功能或 domain 專屬；移除後其他功能仍可運作（如 `features/user/`、`features/project/`、`features/product/`、`features/order/`、`features/my/`）
- `shared/`：無狀態函式庫，不放 controller / service / store；缺少時頁面仍可開，只是部分失效（如 `shared/datetime/`、`shared/lifecycle/`、`shared/routing/`、`shared/string/`）
- 其他頂層目錄：`enum/`、`components/`（共用元件）、`views/`、`types/`、`theme/`
- domain 檔案過多時，可再依 `composable/`、`store/` 或子 domain（`edit/`、`display/`、`auth/`）細分
- 同一 domain 目錄可直接混放不同類型的檔案，不必先細分子目錄：

```
features/flower/
├── useFlower.ts
├── flower.formatter.ts
├── useFlowerController.ts
└── v-flower.ts
```

## View 檔案

- Route 用的 View 命名 `xxxView.vue`，每個 View 放獨立資料夾（即使只有一個檔案）
- View 專屬的 `components/`、`composable/` 放該資料夾內，不得被其他 view 引用
- 有子路徑的 View：子頁放子目錄（如 `views/user/edit/`），外層 `UserEditView.vue` 以 `<RouterView />` 載入
- 巢狀路由：父 route 設 `redirect` 與 `children`，子 route 指向子 View

```typescript
{
  path: '/user/edit/:id',
  name: 'user_edit',
  redirect: { name: 'user_edit_info' },
  component: () => import('~/views/user/UserEditView.vue'),
  children: [
    {
      path: 'info',
      name: 'user_edit_info',
      component: () => import('~/views/user/edit/UserEditInfoView.vue'),
    },
  ],
}
```
