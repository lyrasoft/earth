# Flower MVC Work Plan

- $stage: `Admin`
- $subject: `Flower`

## Goal

<!-- agent: Briefly describe the problem this feature solves -->

## References

<!-- agent: Describe the documents the agent should read before generating the MVC; paths must start from the project root -->

- [Windwalker MVC Architecture](https://windwalker.io/documentation/unicorn/mvc-crud/mvc-concepts.md)
- `.agents/skills/windwalker/references/development-app/mvc-generation.md` MVC generation guide

## Table (`flowers`)

| Column | Type | PK  | Null | Default | Special Type  | Description |
| ---- | ---- |---| ---- | ---- |---| ---- |
| id | int AI | Y  | N | |   | PK |
| type   | varchar(255)       |    | N  | | enum:FlowerType  |    |
| prefix   | varchar(255)       |    | N  | |   |    |
| title | varchar(255) |   | N | '' |   | Name |
| state | bool |   | N | 1 | enum:BasicState  | Publish state |
| details | json |   | N | 1 | FlowerDetails  | Details |
| ordering | int |   | N | 0 |   | Ordering |
| created | datetime |   | Y | |   | Created time |
| created_by | int |   | N | 0 |   | Creator |
| modified | datetime |   | Y | |   | Modified time |
| modified_by | int |   | N | 0 |   | Modifier |

### Indexes

<!-- agent: Use lists and a structured format as much as possible so the user can edit -->

- Indexes
  - created
  - type,prefix
- Unique Keys
  - type,prefix

### Related Tables

<!-- agent: Plan related tables according to the task requirements; each must still have an Entity, not just a plain-text table name -->

## Form Fields

<!-- agent: Use lists and a structured format as much as possible so the user can edit -->

List only the manually specified ones; other fields are generated automatically by the generator.

- type: `FlowerTypeField` -> FlowerType enum
- prefix: `FlowerPrefixField`
- details: `RepeatableField`, allows adding multiple `FlowerDetail` items, each `FlowerDetail` has:
  - title: `TextField`
  - description: `TextareaField`
  - date: `CalendarField`

<!-- agent: Generate the following sections as needed -->

## Execution Steps

<!-- agent: These steps must also be generated from the actual plan and documentation recommendations, so the user can decide how far to execute at a time -->

1. Create migration and Entity
2. Run the generator
3. Adjust Form / View
4. Implement Services and other logic
5. Test
