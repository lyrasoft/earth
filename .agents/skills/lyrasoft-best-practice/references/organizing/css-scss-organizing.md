# CSS / SCSS Best Practices

- Use Bootstrap 5 as the foundation. Change its variables with `sass` and recompile instead of importing the original CSS and extensively overriding it.
- Write HTML with a well-structured flex layout.

## Overriding Bootstrap

```sass
// Bad: directly override component styles and hardcode colors
.btn-primary {
  background: #FF6666;
  border: 1px solid #FF6666;
  border-radius: 8px;
}

// Good: override Bootstrap variables in _variables.scss
$primary: #355B81;
$btn-border-radius: 8px;

// Good: override styles only when variables cannot be adjusted and the style is definitely shared site-wide
.btn {
  transition: ...;
}

// OK: a small override inside a component is not obviously wrong, but changing a variable is still preferred
.btn-primary {
  background: #355B81;
}
```

For details, see [Customize Bootstrap variables and colors](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap).

## Main entry

Load `main.scss` on every page, in this order: variables → bootstrap → base → components → layout → pages.

```sass
// Bad: variables are not loaded first, so Bootstrap cannot apply custom settings
@import "bootstrap/scss/bootstrap";
@import "variables";

// Bad: directly import compiled Bootstrap CSS
@import "bootstrap/dist/css/bootstrap.min.css";

// Good: variables → Bootstrap → project styles
@import "variables";
@import "bootstrap/scss/bootstrap";

@import "base/base";
@import "components/card";
@import "layout/header";
@import "pages/about";
```

Build commands:

```
sass scss/main.scss css/main.css --load-path=node_modules
sass scss/main.scss css/main.css --load-path=node_modules --watch   # Watch for changes
```

For VitePress, see [GitHub project initialization](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/64192529/Github#%E4%BD%BF%E7%94%A8-Vitepress-%E9%80%B2%E8%A1%8C%E5%88%87%E7%89%88).

## Directory structure

- `base/`: foundational project styles
- `components/`: reusable component styles
- `layout/`: layout styles
- `pages/`: styles for each page

Example: <https://github.com/lyrasoft/arendelle-static/tree/staging/.vaseman/resources/assets/scss>

## Page wrappers

Wrap each page's styles in its own wrapper. Do not treat the page class as a BEM block or apply it directly to the whole page.

```sass
// Bad: the page class affects the whole page, and inner BEM elements cannot be reused across pages
.page-about {
  padding: 3rem;
  &__header {}
  &__title {}
}

// OK: there is a wrapper, but inner names are still tied to the page and are difficult to reuse across pages
.page-about-wrapper {
  .page-about {
    padding: 3rem;
    .page-about-title {}
  }
}

// Good: the wrapper provides an independent scope, and inner block names are not tied to the page, so they can be reused
.page-about-wrapper {
  .page-about {
    padding: 3rem;
    background: white;

    .page-header {}
    .page-title {}
    .page-body {}
  }
}
```

- VitePress: do not put styles in a component's `style` block; put them in `scss/pages/`.

## References

- Standard layout template: <https://github.com/lyrasoft/outsource-layout-template/tree/main/resources/assets/scss>
- Real-world example: <https://github.com/lyrasoft/arendelle-static/tree/staging/.vaseman/resources/assets/scss>
- Design System implementation standards: <https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/58982402/Design+System>
- Good HTML / CSS practices and common mistakes: <https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/950486/HTML+CSS>
- Common Vue / VitePress mistakes: <https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/114360341/Vue+Vitepress>
