# Design System Implementation Standards

Before creating project pages, complete the Design System page (`/design-system`) and make it the first item in the main menu (if that is not possible, put it in the Footer). See the standard template examples for PHP (`entries/design-system.blade.php`) and Vue (`resources/vue/DesignSystem.vue`).

Adjust component styles to match the Figma Design System, and ensure the following are true at handoff:

- Use Bootstrap 5 SCSS variables for adjustments (see [Customize Bootstrap variables and colors](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345)).
- Do not change the Design System layout.
- Leave unmodified components exactly as they are.
- Add any special, project-specific components in a new section at the bottom.
- Preserve the SCSS files.

## Layout and components

```text
Bad: Make the layout look nicer and remove unused components
Good: Keep the original layout and modify only components covered by the design
```

**Why?** Future team members need to see which components have been handled and which have not. Changing the layout invalidates that assessment.

```text
Bad: Delete or rewrite unmodified BS components
Good: Keep unmodified BS components exactly where they are; if they break, the SCSS was changed incorrectly
```

**Why?** A website may have a second or third phase of changes; removing components causes rework.

```text
Bad: Directly override Bootstrap component styles or delete existing BS components
Good: Inherit and modify additional styles (see the link above); create a separate section for project-specific components
```

If the design is missing something during implementation (for example, pagination is not designed), ask the design team. If a component is not meant to be handled, leave it as is.
