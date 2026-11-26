# Locale Key Naming Conventions

> **Required reading.** Before adding, renaming, or reusing any translation key (`resources/languages/**/*.ini`, PHP `trans()`, or frontend `__()`), read this document first. It is the standard naming strategy for all locale keys; do not invent a different structure.

## Flat INI format

Windwalker language files are flat `.ini` files with no nesting. Represent hierarchy with dot-separated segments in a single key, and always enclose values in double quotes.

```ini
flower.message.delete.confirm="Are you sure you want to delete?"
flower.field.created.by="Created By"
```

Because all keys are normalized (characters other than `A-Za-z0-9` become `.` and letters become lowercase), **dots are also the separators between words**. `visibility_score`, `visibility-score`, and `visibility.score` are the same key. Separate multiple words with dots (`flower.field.created.by`, `flower.prompt.list.action.add`). Do not rely on `_`, `-`, or letter case to distinguish keys.

A key can be both a leaf and a prefix of another key (`sakura.field.created` and `sakura.field.created.by`); this cannot be represented in a nested YAML structure. When a term has derived keys, prefer the `.label` pattern described below.

Keep related keys adjacent and sort them by key name so translators can recognize groups at a glance.

## Namespaces and scope

Keys begin with a top-level domain-based namespace, such as `app.*`, `ui.*`, `form.*`, and `project.*`.

Under a namespace, group sections by feature or page. Each namespace and section forms a **scope**, and keys within a scope are grouped by purpose: `message.*`, `text.*`, `field.*`, `button.*`, `action.*`, and `meta.*`.

```ini
; app scope
app.message.loading="Loading..."
app.message.error="An error occurred."
app.action.save="Save"
app.action.cancel="Cancel"

; geo.vistracker scope
geo.vistracker.message.loading="Loading visibility data..."
geo.vistracker.message.error="Failed to load visibility data."
geo.vistracker.field.visibility.score.label="Visibility Score"
geo.vistracker.field.visibility.score.desc="The overall visibility score for the selected keywords."

; geo.prompt.list scope
geo.prompt.list.message.loading="Loading prompts..."
geo.prompt.list.message.error="Failed to load prompts."
geo.prompt.list.action.add.prompt="Add Prompt"
```

Any level may be an independent scope, but use **no more than three levels before a purpose section**:

1. **Namespace**: the broadest scope, organized by domain.
2. **Page / feature**: the intermediate scope for a page or feature.
3. **Shared layout**: a large layout or component reused by multiple pages.

## Shared terms (fields, headers, metrics)

An important term may appear in charts, tables, reports, icons, buttons, tooltips, and descriptions. Define it **only once** under `field.*` in the namespace, then reuse it everywhere. Put the main text in `field.<term>.label` and descriptions or tooltips in `field.<term>.desc`.

Bad practice (a key for each component):

```ini
geo.metric.sov="Share of Voice"
geo.metric.sov.desc="..."
geo.table.header.sov="Share of Voice"
geo.charts.tooltip.sov="Share of Voice"
geo.charts.tooltip.sov.desc="..."
geo.input.sov.label="Share of Voice"
```

Good practice:

```ini
geo.field.sov.label="Share of Voice"
geo.field.sov.desc="Description of Share of Voice..."
geo.field.sov.any.other.usage="..."

; shared UI controls may use a component-specific namespace
ui.table.header.toggle.all="Toggle All"
```

## Purpose sections

Group keys by purpose, not by component. A separate section for each component makes the hierarchy too deep and fragmented.

| Section | Use |
|------|------|
| `meta.*` | Page titles, layout headers, metadata, descriptions, subtitles, breadcrumbs, and other page- or layout-level text |
| `message.*` | Alerts, messages, Toasts, and notifications |
| `text.*` | Notes, help text, hints, yes/no, and other standalone text |
| `button.*` | Button labels; may include multiple states (text, tooltip, loading) |
| `action.*` | Common action labels (save, cancel, submit) |
| `field.*` | Form field labels, descriptions, placeholders, and validation messages |

Examples of reuse:

- Reuse `text.*`, `field.*`, or `message.*` for table headers, lists, cards, and tooltips.
- Reuse `app.*` / `ui.*` for common controls (select all, clear all, reset all). In this package, use `unicorn.core.*`.
- Reuse `field.*` for form labels, table headers, and chart labels.
- Use `message.*` or `text.*` for help, warnings, hints, and status messages; place standalone text that has no other category under `text.*`.

## Derived keys

When one primary name needs multiple derived strings, append the derived suffix to the primary key. Form fields commonly need labels, descriptions, placeholders, and error messages:

```ini
project.settings.field.title.label="Project Title"
project.settings.field.title.description="The title of the project"
project.settings.field.title.placeholder="Enter project title"
project.settings.field.title.error.required="Project title is required"
project.settings.field.title.error.invalid="Project title is invalid"
```

The shared prefix identifies the group and helps translators find related keys quickly. Be consistent with suffixes such as `label`, `desc` (or `description`), `placeholder`, and `error.*` within the same scope; do not mix `desc` and `description` for the same purpose.

## Variants and abbreviations

Keep terms with a long form, abbreviation, or variant together under the same `field.*` key:

```ini
geo.field.sov.label="Share of Voice"
geo.field.sov.abbr="SoV"
geo.field.aer.sov.label="AI Share of Voice"
geo.field.aer.sov.abbr="AI SoV"
geo.field.aer.sov.full="AI Engine Response Share of Voice"
```

## Naming by meaning and component context

The same English word may have different meanings. `Mentions` might mean the number of mentions in one chart, the times something has been mentioned elsewhere, a form field, or the entities mentioned in a list. `Cited` might mean “has been cited” or “number of citations.” A noun such as `Music` has no plural in English but may have one in other languages, so extract a separate plural key (`music.list`).

Before extracting a noun, check the component and surrounding code:

- **Table header**: inspect the data displayed in the corresponding `td`.
- **Chart**: inspect the purpose of the tooltip, legend, and axis.
- **Form**: inspect the expected label, placeholder, and description for the field.
- **Title**: inspect the singular / plural form and context of the page, section, or API documentation being presented.

Do not reuse a key just because the English text is the same; reuse it only when the **meaning and context** match. Follow any surrounding comments or explanatory text. For true plurals, use `choice()` (see [plural rules](https://windwalker.io/documentation/components/language/plurals.md) in the Windwalker Language documentation).
