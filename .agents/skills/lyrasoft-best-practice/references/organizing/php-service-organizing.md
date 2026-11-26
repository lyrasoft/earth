# Organizing PHP / Windwalker Services

## Basic rules

- Put Services in a `Service` directory and name them by domain (for example, `ProjectService`).
- Do not wrap simple queries (such as a one-line `findOne`); only wrap queries with specific rules.

## Common function naming

- `get*`: returns a final result.
  - A getter with specific rules, such as `getProjectByNo(string $no, bool $forUpdate = false)`, passes `forUpdate` through `ORMOption`.
  - `getXxxOrCreate()`: wraps “create if not found” in `orm->transaction()`.
  - `getCurrentProject()` and `getProjectByTask()`: use `orm->mustFindOne()`.
- `query*`: returns a `Query` for other functions to continue composing (for example, `queryAvailableProjects(Organization $org): Query`).
- `count*`: returns a count (for example, `countAvailableProjects()` calls `query*()->count()`).
- List conditions: do not wrap only the `where` clause in `getProjects($conditions)`; encapsulate common frontend conditions such as `state` and `org_id`.
- User-visible projects: implement `getMyAvailableProjects()` with an `innerJoin` on the membership table over `queryAvailableProjects()`.
- Expired projects: implement `getExpiredProjects()` by comparing `expiry_date` with `chronos($now)`.
- Add `@return Collection<Project>` to functions that return a Collection.

### Query usage

- A Query returned by `query*` can be used directly in `foreach` or further constrained with `where` / `order`.
- If records need to be fetched and then filtered by rules, use a `Generator` (for example, `yield` inside `queryExpiredProjects()`) to create a new iterator.

## Injection rules

Avoid circular injection between ProjectService and TaskService, and avoid implementing `getTasks` in both.

- **Parent-child relationship**: inject only from the parent into the child (Task belongs to Project, so Project injects Task).
- **Dependency relationship**: the dependent injects the dependency (Order depends on Product, so Order injects Product).
- **Retrieving child items**: retrieve them from the child item's service (`queryProjectTasks()` belongs in TaskService).
- **Parent aggregation**: ProjectService should call TaskService only when aggregation is needed (such as `getProjectForDashboard()`).
- If TaskService must retrieve its parent Project, see “Approach for large projects.”

## Approach for large projects

When a Service becomes too large (for example, ProjectService, ProjectRunnerService, and ProjectTaskService each have more than 20 methods, or read and write operations are mixed), split it by role. The naming concepts are similar to those in Vue projects; see @vue-service-organizing.md.

| Role | Responsibility | Naming examples |
| --- | --- | --- |
| Dispatcher | Coordinates and composes actions; delegates to a Writer or Runner | `ProjectDispatcher` |
| Finder / Reader / Selector | Complex filtering, selection, and normalization for reads | `ProjectFinder`, `ProjectSelector` |
| Writer / Updater | Complex data validation and related writes | `ProjectWriter`, `ProjectUpdater` |
| Fetcher | Like a Finder, but reads from a remote API | `ProjectApiFetcher` |
| Runner | Asynchronous, scheduled, or time-consuming background work | `ProjectRunner`, `ProjectTaskRunner` |
| Processor | Actions, progress, and other work for a single operation | `LessonProcessor` |
| Resolver | Normalizes a string or resolves it to a corresponding item | `ShortUrlResolver` |
| Service / Facade / Composer / Orchestrator | Combines multiple services at a higher level | `ProjectService`, `ProjectOrchestrator` |
| Handler | Handles a single event and has no direct DB relationship | `ShareHandler`, `ShortCodeHandler` |
| Validator | Data validation | `PersonalIDValidator` |
| Formatter / Parser | Data conversion or normalization | `AddressFormatter` |

- Use “verb + role” or a common noun as a suffix. Other common roles include Adapter, Creator, Accessor, Monitor, Executor, Normalizer, and Builder.
- Splitting services can resolve circular injection: TaskService injects `ProjectFinder`, while ProjectService injects only `TaskFinder`.

## DDD directory organization

Organize directories by real-world entity (domain). Windwalker's MVC Modules already use DDD concepts.

```
src/
├── Core/
│   ├── Api/          (ApiControllerTrait.php, TokenData.php)
│   └── Auth/         (DatabaseAuthMethod.php, LdapAuthMethod.php, GoogleSocialProvider.php)
├── Features/
│   ├── Project/      (ProjectService, ProjectFinder, ProjectWriter, ProjectDispatcher)
│   └── Task/         (TaskRunner.php)
└── Shared/
    ├── Currency/     (CurrencyFormatter.php, PriceObject.php)
    ├── GitHub/       (GitHubClient.php, GitHubSdk.php)
    └── Utilities/    (TextFormatter.php)
```

- **Core**: foundational logic, Traits, and services shared across the site; the whole site would break without them. Not directly associated with an Entity (for example, Api, Auth, Cache).
- **Features**: specific to a feature or domain.
  - Most correspond directly to Entities and handle DB interactions and business logic (for example, Project, Task, User, Order, Organization).
  - Put a domain here even if it has no corresponding Entity but needs independent handling. For example, `EmailNotifyRunner` may fetch several Entities in a batch, send notifications, and save their status; Notification may include Email / SMS drivers and write to OrderHistory even if there is no `notifications` table.
- **Shared**: stateless functionality or miscellaneous utilities that do not belong to a specific domain and can be used anywhere (for example, Currency, GitHub, DateTime, HTML, Utilities).
  - These affect only a few pages and do not break every page when unavailable. For example, a Slack helper that only has `send()` and does not write information back.
- Do not put all existing Services under Features; split them across the three directories.
- About 80% of Services correspond to Entities and most belong in Features; not every item in Features must correspond to an Entity.

## Existing Windwalker directories (do not move)

Keep these in place because of autogenerated code:

- Component, Cache, Middleware, Entity, Repository, Field, Data, Job
