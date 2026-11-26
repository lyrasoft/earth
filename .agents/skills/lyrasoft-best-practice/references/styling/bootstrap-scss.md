# Bootstrap SCSS Best Practices

When using Bootstrap, customize it through SCSS variables and Sass functions, then recompile, rather than overriding the compiled CSS.

For details, see [Customize Bootstrap variables and colors](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap).

## Modify variables

Variables must be declared before `@import "bootstrap/scss/..."`. Search `bootstrap/scss/_variables.scss` for Bootstrap's default variables.

```scss
// Bad: the variable is declared after the Bootstrap import and has no effect
@import "bootstrap/scss/variables";
$primary: #d96ed9;

// Good: declare variables before the import
$primary: #d96ed9;

@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";
@import "bootstrap/scss/variables-dark";
@import "bootstrap/scss/mixins";
@import "bootstrap/scss/maps";
```

## Add theme colors and color variables

Adding a color to `$theme-colors` automatically generates `text-*`, `bg-*`, and `btn-*` classes. Merging `$colors` outputs CSS variables (such as `--bs-golden`).

```scss
// Bad: hardcoded color with no corresponding Bootstrap class or CSS variable
.text-facebook { color: #4267B2; }

// OK: add only to theme-colors; rgba utility classes will not be available
$facebook: #4267B2;
$theme-colors: map-merge($theme-colors, ("facebook": $facebook));

// Good: add to theme-colors and include rgb and utility classes
$facebook: #4267B2;
$custom-theme-colors: ("facebook": $facebook);

$theme-colors: map-merge($theme-colors, $custom-theme-colors);
$theme-colors-rgb: map-loop($theme-colors, to-rgb, "$value");
$utilities-colors: map-merge($theme-colors-rgb, $utilities-colors);
$utilities-text-colors: map-loop($utilities-colors, rgba-css-var, "$key", "text");
$utilities-bg-colors: map-loop($utilities-colors, rgba-css-var, "$key", "bg");

// Good: merge $colors to generate CSS variables
$colors: map-merge($colors, ("golden": #D4A017));
```

## Adjust component parameters

```scss
// Bad: hardcode border radius in components, making it difficult to change consistently later
.card { border-radius: 15px; }
.btn { border-radius: 5px; }

// Good: adjust consistently through variables
$link-decoration: none;
$card-border-radius: 15px;
$btn-border-radius: 5px;
$input-border-width: 0;

@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";
```

## Custom project variables

```scss
// Bad: repeat the same hardcoded font in multiple places
.card-title { font-family: 'Roboto', 'PingFang TC', sans-serif; }

// Good: define a variable and use it consistently
$title-font: 'Roboto', 'PingFang TC', 'Heiti TC', '微軟正黑體', sans-serif;

.card {
  .card-title {
    font-family: $title-font;
    color: $primary;
  }
}
```

## Make use of Sass and Bootstrap functions

```scss
// Bad: hardcoded hover color is disconnected from the primary color
a:hover { color: #b35a5a; }

// Good: derive the color from a variable with darken()
a {
  color: $primary;

  &:hover {
    color: darken($primary, 5%);
  }
}

// Good: quickly generate button styles with the button-variant mixin
.btn-custom {
  @include button-variant(#581493);
}
```

Reference: [Sass functions](https://sass-lang.com/documentation/at-rules/function)

## Modify class styles

Override built-in classes only for site-wide styles that cannot be adjusted with variables. Use a separate class for styles specific to a section.

```scss
// Bad: override Bootstrap classes inside a page; the scope is unclear
.about-page {
  .btn {
    transition: ...;
  }
}

// Good: override a built-in class for a site-wide style
.btn {
  transition: ...;
}

// Good: use a separate class for a section-specific style
.about-page {
  .btn--more {
    transition: ...;
  }
}
```

## Custom utility classes

When extending spacers or adding classes with `$utilities`, merge with existing values instead of overwriting them.

```scss
// Bad: overwrite the map and lose Bootstrap's default utilities
$utilities: (
  "ft": (property: font-size, class: ft, values: (58: 3.6rem, 64: 4rem))
);

// Good: merge with the existing map
$spacers: map-merge((6: ($spacer * 6), 7: ($spacer * 12)), $spacers);

$utilities: map-merge(
  $utilities,
  (
    "ft": (property: font-size, class: ft, values: (58: 3.6rem, 64: 4rem)),
  )
);
```

## Make use of CSS variables

```scss
// Good: use CSS variables directly
.foo { color: var(--bs-primary); }

// Bad: rgba() does not accept a complete color variable
.foo { background-color: rgba(var(--bs-primary), .5); }

// Good: use the -rgb version for transparency
.foo { background-color: rgba(var(--bs-primary-rgb), .5); }
```

Custom CSS variables:

```scss
$special-color: #D18B3B;

:root {
  --my-color: #f5f5f5;
  --banner-bg: #{$primary};
  --special-color: #{$special-color};
  --special-color-rgb: #{to-rgb($special-color)};
}
```

## Common issue

### Button text turns black

When the design's primary color is light, Bootstrap automatically changes the text to black. Lower the contrast threshold in `_variables.scss` before importing Bootstrap (commonly to a value between 2.7 and 3):

```scss
$min-contrast-ratio: 2.8;
```
