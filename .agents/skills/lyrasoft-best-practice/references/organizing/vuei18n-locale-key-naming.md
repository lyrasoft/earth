# VueI18n Locale Key Naming Conventions

## Namespaces and scope

Organize translation keys into top-level, domain-based namespaces such as `app.*`, `ui.*`, `form.*`, and `project.*`.
Within each namespace, group keys into sections by feature or page.

Each namespace and section forms a scope. Within a scope, group keys by purpose, such as `message.*`, `text.*`, `field.*`, and `button.*`.
For example, the `geo.*` namespace covers the platform's GEO feature, with sections such as `geo.visibility.*`, `geo.prompt_list.*`, and `geo.compare.*`.

Example:

```yaml
app:
    # app scope
    message:
        loading: "Loading..."
        error: "An error occurred."
    action:
        save: "Save"
        cancel: "Cancel"
geo:
    # geo scope
    vistracker:
        # geo.vistracker scope
        message:
            loading: "Loading visibility data..."
            error: "Failed to load visibility data."
        field:
            visibility_score:
                label: "Visibility Score"
                desc: "The overall visibility score for the selected keywords."
    prompt_list:
        # geo.prompt_list scope
        message:
            loading: "Loading prompts..."
            error: "Failed to load prompts."
        action:
            add_prompt: "Add Prompt"
```

Any level can form its own scope, but it is recommended not to exceed three levels:

1. Namespace: the broadest scope, organized by domain.
2. Page / feature: the intermediate scope for a page or feature.
3. Shared layout: a large layout or component with complete page-level functionality that can be reused by multiple pages.

Each scope can define its own keys such as `message.*`, `text.*`, `field.*`, and `button.*` for use within that scope.

## Naming shared terms (fields, headers, metrics, etc.)

Important terms in each namespace may appear in charts, tables, reports, icons, buttons, hints, and descriptions.
Use shared `field.*` keys in that namespace rather than creating separate keys for each component.
Put the primary field text in `field.*.label` and explanatory or hint text in `field.*.desc`.
Reusing the same key across components keeps terminology consistent and easy to maintain.

Bad example:

```yaml
geo:
    metric:
        sov: "Share of Voice"
        sov_desc: "..."
    table:
        header:
            sov: "Share of Voice"
    charts:
        tooltip:
            sov: "Share of Voice"
            sov_desc: "..."
    input:
        sov:
            label: "Share of Voice"
```

Good example:

```yaml
geo:
    field:
        sov:
            label: "Share of Voice"
            desc: "Description of Share of Voice..."
            any_other_usage: "..."

ui:
    table:
        # Shared table UI controls can use a component-specific namespace.
        header:
            toggle_all: "Toggle All"
```

This keeps terms used across multiple contexts together under a single `field.*` key.

## Grouping UI components and shared strings by purpose

In addition to shared terms and fields, group keys into shared sections by purpose so translators can understand each string's use and context.

These sections include, but are not limited to:

- `meta.*`: page titles, layout titles, metadata, descriptions, subtitles, breadcrumbs, and other page-level or layout-level text
- `message.*`: alerts, messages, toasts, and other notifications
- `text.*`: notes, help text, hints, yes/no, and other standalone text
- `button.*`: common button action labels, including multiple interaction states (text, tooltip, loading)
- `action.*`: common action labels (save, cancel, submit, etc.)
- `field.*`: form field labels, descriptions, placeholders, and validation messages

Group keys by purpose, not by component; otherwise the translation hierarchy becomes too deep and fragmented.

Examples of key reuse:

- Headers, lists, cards, and tooltips in page components can often reuse keys under `text.*`, `field.*`, or `message.*`.
- Common controls such as select all, deselect all, clear all, and reset all can reuse keys under `app.*` or `ui.*`.
- Form labels, headers, and chart labels can often reuse terms under `field.*`.
- Help text, warnings, hints, and status messages can use `message.*` or `text.*`. Put standalone text that does not fit another category under `text.*`.

### Derived keys

When a UI component or feature needs multiple strings derived from one primary name / key, put the derived suffix after the primary key.

Forms are the most common case. For example, the `project.settings.field.title` field may need a label, description, placeholder, and error messages, all derived from the primary key:

```yaml
project:
    settings:
        field:
            title:
                label: "Project Title"
                description: "The title of the project"
                placeholder: "Enter project title"
                error:
                    required: "Project title is required"
                    invalid: "Project title is invalid"
```

The shared prefix or suffix makes related keys easy to recognize and helps translators find them quickly.

### Variants and abbreviations

Terms may have many forms, long names, or multiple variants and abbreviations. When storing them under `field.*`, keep these forms together under the same key as in the example below, making them easier for translators to understand and maintain.

```yaml
geo:
    field:
        sov:
            label: "Share of Voice"
            abbr: "SoV"
        aer_sov:
            label: "AI Share of Voice"
            abbr: "AI SoV"
            full: "AI Engine Response Share of Voice"
```

### Naming by meaning and component context

The same word can mean different things in English and other languages. For example, `Mentions` may mean the number of mentions in one chart, the times something has been mentioned in another, “mention” in a form, or the entities mentioned in a list or query history.

`Cited` may mean “has been cited” or “number of citations.”

The English word `Music` has no plural form, but other languages may; therefore, the plural key (`music_list`) must be extracted separately for translation.

When you encounter a noun in a component that needs to be extracted for translation, first check the component context and surrounding code, for example:

- Table header: check the data displayed in the corresponding cell or `td`.
- Chart: check the purpose of the chart tooltip, legend, and axis.
- Form: check the expected response content for the field's label, placeholder, and description.
- Title: check singular / plural forms and the application context of the page, or the content and API documentation shown in that section.

This prevents same-named keys from being used incorrectly. Also consult any surrounding comments or explanatory text.

### Enum values

Since all enums are shared globally, put them under `app.enum.*`. Likewise, put each case's keys under subkeys such as `title`, `desc`, `short`, and `full`, as with `field.*`.

```yaml
app:
    enum:
        sentiment:
            positive:
                title: "Positive"
                desc: "The sentiment is positive."
            negative:
                title: "Negative"
                desc: "The sentiment is negative."
            neutral:
                title: "Neutral"
                desc: "The sentiment is neutral."
```

## Existing namespaces

- `app.*`: shared UI strings and globally shared enum values. **Always prefer `app.*` keys; do not redefine common strings in feature namespaces.**
- `form.*`: form field hints, validation messages, multiselect attributes, and file uploader strings
- `ui.*`: general UI framework elements such as pagination, navigation sidebar, table controls, and language settings
- `project.*`: project lists, creation wizard, overview, settings, sharing, and date ranges; use `project.overview.*` for overview page strings
- `onboarding.*`: setup steps, project preview, and trial checkout; use `onboarding.button.*` for shared step actions
- `auth.*`: authentication / login
- `bill.*`: billing and subscription domain. `bill.usage.*`, `bill.addon.*`, `bill.currency.*`, and `bill.field.*` contain shared usage, quota, and currency terms; `bill.billing.*` is the billing / invoice history page scope (receipt table, download / preview); `bill.plan.*` is the plan overview page scope (current plan card, pricing cards, tracked-unit add-ons, plan change / cancellation dialogs)
- `seo.*`: rank tracking and keywords
- `geo.*`: GEO, AI visibility, and prompts
- `unified.*`: shared SEO / GEO terminology; cross-channel and SERP metrics use `unified.field.*`, including `label` / `abbr` / `desc` variants. AI-specific metrics remain under `geo.field.*`.

When adding keys or a feature namespace, update both locale YAML files. No Nuxt configuration change is needed. When adding a language, create `app/i18n/locales/{locale}.yaml` with the same key structure and register it in `nuxt.config.ts → i18n.locales[]`.

### `app.*` keys

`app.*` contains the following subnamespaces:

- `app.action.*`: common button labels (save, save_settings, submit, cancel, add, edit, update, close, back, next, previous, search, clear, reset, refresh, view, import, export, upload, download, back_to_top)
- `app.status.*`: status and feedback messages (loading, saving, processing, success, error, done, saved, deleted, no_data, coming_soon, beta, not_available)
- `app.confirm.*`: confirmation dialog text (unsaved_changes, leave, leave_desc)
- `app.enum.*`: globally shared translations for enum cases, including `title` / `desc` variants
- `app.footer.*`: footer links and copyright

### `auth.*` keys

`auth.login.meta.title`, `auth.register.meta.title`, and `auth.logout.meta.title` are page titles.
Login, logout, and ticket progress text belongs in the respective `message.*` groups.
Keep user menu navigation and actions in the existing `auth.user_menu.*` group.

### `form.*` keys

`form.*` covers all form- and input-related UI strings:

- `form.field.*`: shared field placeholders and validation messages (such as `url.error.invalid`, `error.required`, and `select.placeholder`).
- `form.text.*`: shared required / optional labels and hints.
- `form.multiselect.*`: shared control strings, grouped under `button.*`, `field.*`, `message.*`, and `text.*`.
- `form.uploader.*`: file uploader actions, status messages, and hints, grouped under `button.*`, `message.*`, and `text.*`.

### `ui.*` keys

`ui.*` covers general UI framework elements that are not specific to a particular feature:

- `ui.pagination.*`: `prev`, `next`, `page`, `of`
- `ui.nav.*`: sidebar navigation labels: `my_keywords`, `my_projects`, `billing`, `guide`, `create_project`
- `ui.table.*`: table controls: `per_page`
- `ui.settings.*`: language settings panel: `language_title`, `language_label`
