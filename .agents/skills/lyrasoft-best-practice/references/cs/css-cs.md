# CSS / SCSS Code Style

# Introduction

LYRASOFT SCSS coding style follows the [Airbnb CSS/Sass Styleguide](https://github.com/airbnb/css).

The guidance below supplements the Airbnb rules. Since much of the second half of the Airbnb CSS/Sass Styleguide is advisory rather than mandatory, some of our additions may conflict with it.

# Syntax and formatting

## Indentation

Use 2 spaces for indentation in all CSS, SASS, and LESS files.

Four spaces are acceptable when writing CSS in HTML or PHP, since our HTML / PHP uses four-space indentation. This follows the same rule as `php-cs.md`.

## Rule declarations

Each rule declaration must be on its own line.

```css
/* Correct */
.example {
  color: #000;
  visibility: hidden;
}

/* Incorrect - written on the same line */
.example {color: #000; visibility: hidden;}
```

## Alignment

### Selectors and declarations

The opening brace must be on the same line as the selector and preceded by one space. The closing brace must appear on its own line after the last property, at the same indentation level as the opening brace.

```css
/* Correct */
.example {
  color: #fff;
}

/* Incorrect - closing brace is misplaced and incorrectly indented */
.example {
  color: #fff;
  }
```

Put each selector on its own line, with the last selector on the same line as the opening brace:

```css
.foo,
.foo-bar,
.baz {
  display: block;
}
```

### Prefix alignment

For cross-browser engine declarations, it is recommended (but not required) to use spaces to align indentation, for example:

```css
.foo {
  -webkit-border-radius: 3px;
     -moz-border-radius: 3px;
          border-radius: 3px;
}
```

## HEX values

HEX values should be lowercase and use the shortest possible notation:

```css
/* Correct */
.example {
  color: #eee;
}

/* Incorrect */
.example {
  color: #EEEEEE;
}
```

## Units for zero values

Do not specify a unit for a zero value.

```css
/* Correct */
.example {
   padding: 0;
}

/* Incorrect - uses a unit */
.example {
   padding: 0px;
}
```

# Naming patterns

Airbnb recommends BEM or OOCSS naming. Their implementation may differ as shown below, using `card` as an example:

BEM style:

```css
.listing-card { }
.listing-card--blue { }
.listing-card__title { }
.listing-card__body { }
```

OOCSS style:

```css
.listing-card { }
.listing-card.card--blue { }
.listing-card .card-title { }
.listing-card .card-body { }
```

We currently recommend BEM as the primary style and OOCSS when BEM cannot be used. See our BEM conventions: [\[CSS\] BEM Concepts and Conventions](https://lyrasoft.atlassian.net/wiki/spaces/MEG/pages/142213177/CSS+BEM)

# SCSS style

Basic SCSS conventions follow Airbnb recommendations, but nesting beyond three levels is allowed. We primarily develop websites rather than reusable libraries; HTML in most large projects is extremely complex and deeply nested, and three nesting levels are insufficient for OOCSS/BEM styles. This can easily cause website elements to affect one another.

The following Bootstrap example uses multiple levels of child selectors (`>`) in some complex, deeply nested components (such as dropdowns) to ensure that the correct elements are selected.

```css
.navbar-expand {
  @each $breakpoint in map-keys($grid-breakpoints) {

    &#{$infix} {
      // ...

      @include media-breakpoint-up($next) {
        .navbar-nav {
          flex-direction: row;

          .dropdown-menu-right {
            right: 0;
            left: auto; // Reset the default from `.dropdown-menu`
          }
        }

        // For nesting containers, have to redeclare for alignment purposes
        > .container,
        > .container-fluid {
          flex-wrap: nowrap;
        }

        // ...
      }
    }
  }
}
```

Most ordinary components can be kept within five levels. Optimize hierarchy and structure as much as possible to keep the code readable. Consider the following rules:

The first level is the component entry selector. A self-contained section that needs its own styles is called a component; all its styles should be nested inside it:

```scss
header.main-header {
  // ...
}

section.feature {
  // ...
}
```

Within a component entry, class and element selectors should be no more than three levels deep:

```scss
header.main-header {
  ul.dropdown-menu {
    > li {
      > a {
        // ...
      }
    }
  }
}
```

For subsequent selectors, use only pseudo-element selectors (`::`) or the `&` selector to change styles for different states.

```scss
header.main-header {
  ul.dropdown-menu {
    > li {
      > a {
        &::before {
          // ...
        }

        &:hover {
          // ...
        }

        &.active {
          // ...
        }
      }
    }
  }
}
```

However, existing classes that change by hierarchy can reduce the number of levels needed. This is the improved structure provided by Bootstrap 4 and later:

```css
header.main-header {
  .navbar-nav a.nav-link {
    // Main menu item
  }

  a.dropdown-item {
    // Submenu item
  }
}
```
