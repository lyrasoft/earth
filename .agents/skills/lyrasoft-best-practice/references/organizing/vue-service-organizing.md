# Modern Vue Project Structure and Syntax Conventions

## Coding conventions

- Service / Controller: use composables (`useXxx()`), not classes.
- Functions: prefer `function` declarations (they are hoisted and can appear after their calls); prefer them inside composables as well.
- Arrow functions are allowed when they are one-liners and numerous, wrap another function (such as `useDebounceFn`), or need an explicit type. They must be declared before being called.
- Do not use `const foo = function () {}` (variable assigned an anonymous function).
- Always use named exports for composables, not `default`.
- Initialize hooks (such as `useRouter()`) at the outermost level of `useXxx()` and use them directly in inner async functions; do not call hooks inside async functions.

## Shared composables

- Per-page state: use a regular `useXxx(options)` that creates a new ref on each call (for example, `useListFetcher`).
- State shared across pages: use `createGlobalState()` ([vueuse](https://vueuse.org/shared/createGlobalState/)) and initialize it only once.

## File naming and purpose

| Type | Naming | Purpose |
| --- | --- | --- |
| Composable | `useFoo.ts`, `onRouteUpdated.ts`, `createApiClient.ts` | Encapsulates functionality called independently within a component; it expires when the component exits. It can interact through global variables in a store. Start with a verb and use camelCase. Related functionality for the same entity can share a file; unrelated functionality should not. |
| Store | `*.store.ts` (such as `auth.store.ts`) | Global variables; contains only getters / setters and data conversion, not business logic (register / login / create / delete, or remote API calls belong in a controller / service). |
| Service | `useFlowerService.ts`, `flower.service.ts` | Business logic, API calls, and CRUD for an individual item; reused from multiple places. |
| Controller | `useFlowerController.ts`, `flower.controller.ts` | Page navigation and data preparation before API calls; can compose multiple services. After an API response, writes to the store through a setter. Small and medium projects may omit a Service. |
| Miscellaneous functions | `flower.ts`, `unit.converters.ts`, `currency.formatters.ts` | Collections of stateless pure functions without storage; use lowercase kebab-case or plural nouns. |
| Transitional classes | `main.controller.ts`, `user.service.ts` | Files that are still classes; do not add more of these in the future. |

- A simple stateless function starting with `use` (such as `useScrollToTop(root)`) can go in a miscellaneous functions file.
- Functions that return stateful content such as refs or methods must be composables.
- Store wrappers for global core variables are named `store` even when written as composables (with `createGlobalState`), such as `useAuthStore`.

## Service role suffixes (with `use` prefix)

- Give each kind of functionality its own composable; do not mix editing, layout, and formatting.
- `useXxxStore`: low-level globally shared variables.
- `useXxxEditor`: editing state (`editingItem`, `edit()`, `cancel()`, `save()`), often shared across pages.
- `useXxxPresenter` or a plain noun (such as `useBreadcrumb`): visual and layout state, similar to a Windwalker ViewModel or `XxxViewService`; usually wrapped with `createGlobalState`.
- `useXxxController`: corresponds to an API entry point and handles API calls that need to be shared.

## Directory structure (DDD)

- At the top level of `src/`, use `core`, `features`, and `shared`, then subdirectories by domain.
- `core/`: shared site-wide or initialization code; without it the whole site will not load (such as `core/main/`, `core/api/`, `core/events/`).
- `features/`: specific to a feature or domain; removing it leaves other features operational (such as `features/user/`, `features/project/`, `features/product/`, `features/order/`, `features/my/`).
- `shared/`: stateless libraries; do not put controllers / services / stores here. Without them, pages still load but some functionality may fail (such as `shared/datetime/`, `shared/lifecycle/`, `shared/routing/`, `shared/string/`).
- Other top-level directories: `enum/`, `components/` (shared components), `views/`, `types/`, `theme/`.
- When a domain has many files, further divide it by `composable/`, `store/`, or subdomain (`edit/`, `display/`, `auth/`).
- Files of different types can be placed directly in the same domain directory; there is no need to divide them into subdirectories first:

```
features/flower/
├── useFlower.ts
├── flower.formatter.ts
├── useFlowerController.ts
└── v-flower.ts
```

## View files

- Name route Views `xxxView.vue` and put each View in its own directory, even if it contains only one file.
- Put View-specific `components/` and `composable/` directories inside that View directory. Other Views must not reference them.
- For a View with child routes, put child pages in subdirectories (such as `views/user/edit/`) and load them through `<RouterView />` in the parent `UserEditView.vue`.
- For nested routes, set `redirect` and `children` on the parent route, and point child routes to child Views.

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
