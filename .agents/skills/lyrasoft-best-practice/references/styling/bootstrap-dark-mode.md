# Bootstrap Dark Mode Best Practices

Bootstrap 5.3.0 and later supports dark mode. Use the dark mode feature instead of manually changing every light-mode background and text color to dark colors.

For details, see [Bootstrap 5.3 Color modes](https://getbootstrap.com/docs/5.3/customize/color-modes).

## Version requirement

Dark mode requires Bootstrap 5.3.0 or later.

```json
// Good
"dependencies": {
  "bootstrap": "^5.3"
}
```

## Enable Dark Mode

```html
<!-- Good: add data-bs-theme to html to switch the whole site to dark mode -->
<html data-bs-theme="dark">

<!-- Good: add data-bs-theme to any region to switch only that region to light or dark mode -->
<div data-bs-theme="dark">...</div>
```

## Button text color

If inverse button text becomes black in dark mode, follow [Customize Bootstrap variables and colors](https://simular-outsourcing.atlassian.net/wiki/spaces/layout/pages/852345/Bootstrap#%E6%8C%89%E9%88%95%E6%96%87%E5%AD%97%E8%AE%8A%E9%BB%91%E8%89%B2).

## Invert a single region

On a white-background site, regions such as the Footer may need a dark background with white text.

```html
<!-- Bad: links become pure white or cannot follow the theme, making adjustments difficult -->
<div class="l-footer bg-dark text-light">...</div>

<!-- Good: text-bg-* automatically pairs the background with a contrasting text color -->
<div class="l-footer text-bg-dark">...</div>

<!-- OK: use data-bs-theme and text-body to switch the entire region to dark mode -->
<div class="text-body" data-bs-theme="dark">...</div>
```

**Explanation:**

- `text-bg-*` sets an appropriate text color when the background color changes; it is suitable for components such as badges and cards.
- The `data-bs-theme` approach requires that dark mode has not been disabled in `_variables.scss`.

```html
<!-- Bad: use if/else to determine the text color based on the color -->
<span v-if="themeColor === 'warning' || themeColor === 'info'"
  :class="`badge bg-${ themeColor } text-dark`">{{ themeColor }}</span>
<span v-else :class="`badge bg-${ themeColor }`">{{ themeColor }}</span>

<!-- Good: solve it in one line with text-bg-* -->
<span class="badge" :class="`text-bg-${ themeColor }`">{{ themeColor }}</span>
```

Reference: [Color background](https://getbootstrap.com/docs/5.3/helpers/color-background/)

## Change link colors

```html
<!-- Bad: text-primary loses its hover color effect -->
<a class="text-primary" href="#">Link</a>

<!-- Good: link-primary preserves the hover effect -->
<a class="link-primary" href="#">Link</a>
```

Reference: [Colored links](https://getbootstrap.com/docs/5.3/helpers/colored-links/)
