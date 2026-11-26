# CSS BEM Concepts and Conventions

# Scope

Use BEM for class naming in projects that use traditional CSS or SCSS, such as projects combining Bootstrap with handwritten CSS.

BEM does not apply when the current environment uses Tailwind CSS or another CSS-in-JS approach.

# Basic concepts

The basic BEM concepts are:

- Block `.block`
- Element `.block__element`
- Modifier `.block__element--modifier`

Use these three levels to compose the HTML class structure.

For longer words, use a single `-` as a separator; for example, `main-block` is treated as one block name.

## When to use a block

Any clearly defined functional section can begin with a block. A block can contain more blocks without depending on them.

## When to use an element

Use elements to distinguish the purposes of content within a block.

For example:

```html
<div class="card">
  <div class="card__header">

  </div>

  <div class="card__body">

  </div>
</div>
```

## When to use a modifier

Use a modifier on the same element as a block or element class to represent a state or type variation.

A block and an element must not exist on the same element.

State example:

```
<a href="#" class="c-button c-button--active"></a>
```

Type example:

```
<button class="c-button c-button--primary"></button>
```

# Do not nest elements

Try to keep the hierarchy flat. The following is not recommended:

```
<div class="card">
    <div class="card__header">
        <h2 class="card__header__title">
            Hello
        </div>
    </div>
</div>
```

Recommended:

```
<div class="card">
    <div class="card__header">
        <h2 class="card__title">
            Hello
        </div>
    </div>
</div>
```

# When to split out a new block

There is no fixed rule, but when necessary, use a longer name for a new, lower-level block. In general, avoid double-nested elements.

```
<div class="header">
    <ul class="header-menu">
        <li class="header-menu__link">
            Link Text
        </li>
    </ul>
</div>
```

You can also consider treating an element as a new block at the next level:

```
<div class="header">
    <ul class="header__menu menu">
        <li class="menu__link">
            Link Text
        </li>
    </ul>
</div>
```

If you are concerned about block name collisions, see the Namespace section below.

## Do not use an element across blocks

Do not let the hierarchies of two blocks overlap.

Bad:

```html
<div class="main-section">
    <header class="header">
        <div class="main-section__title">
            <a class="header__link" href="...">
            </a>
        </div>
    </header>
</div>
```

Good:

```html
<div class="main-section">
    <header class="header">
        <div class="header__title">
            <a class="header__link" href="...">
            </a>
        </div>
    </header>
</div>
```

## Do not reach down into loops

This makes it difficult to restructure the page.

Bad:

```html
<div class="l-lesson-list">
    @foreach($leesons as $lesson)
    <div class"l-lesson-list__item">
        <h3 class="l-lesson-list__title">{{ $lesson->getTitle() }}</h3>
        ...
    </div>
    @endforeach
</div>
```

Good:

```html
<div class="l-lesson-list">
    @foreach($leesons as $lesson)
    <div class"c-lesson-item">
        <h3 class="c-lesson-item__title">{{ $lesson->getTitle() }}</h3>
        ...
    </div>
    @endforeach
</div>
```

# Namespace

Use Namespace abbreviations to quickly distinguish block characteristics. Common namespaces include:

| Type | Prefix | Examples | Description |
| --- | --- | --- | --- |
| Component | `c-` | `c-card` `c-checklist` | A component or important section. A Component is an independently encapsulated small utility, such as a button, link, card, or input. It may be reused, but does not have to be. |
| Layout | `l-` | `l-grid` `l-product-page` | Layout and page composition. Usually not reused; used simply to divide a page into sections. |
| Helper | `h-` | `h-show` `h-hide` | Helper classes for quickly applying CSS styles. |
| States | `is-` `has-` | `is-active` `has-loaded` | Used to express state differences. |
| Javascript Hooks | `js-` | `js-select2` | Classes used specifically by JS or jQuery selectors. |

An element can belong to both a Layout and a Component, for example:

```
<div class="l-product-intro c-product-intro"
```

## A more complete example

```
<div class="l-product-page">
    <div class="l-product-page__container container">
        <div class="row">
            <div class="col-lg-6 l-product-images">
                <div class="c-product-cover">
                    <img class="c-product-cover__image" src="#" alt="#">
                </div>

                <div class="c-product-thumb">
                    <img class="c-product-thumb__image" src="#" alt="#">
                </div>
            </div>

            <div class="col-lg-6 l-product-intro c-product-intro">
                <div class="c-product-intro__header">
                    <h2 class="c-product-intro__title">Title</h2>
                </div>

                <div class="c-product-intro__price c-intro-item">
                    <div class="c-intro-item__label">
                        Price
                    </div>

                    <div class="c-intro-item__value">
                        $NT 130
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

To understand Components and Layouts, see this article: [\[Frontend\] What are Components & Layouts?](https://lyrasoft.atlassian.net/wiki/spaces/MEG/pages/1684701573/Component+Layout)

# Hierarchy and order

Because we use BEM, a block name must always be at the top level. HTML sometimes needs several levels to achieve a visual effect, so we define common naming patterns to keep these levels consistent.

## Wrapper

When a block needs an inner element to define spacing, name it `__wrapper`, followed by `__inner`.

For deeper hierarchies, you can add `__body` to wrap the main elements at a lower level, and `__content` at the innermost level to wrap the actual content. The order should be as follows. Not every level is required, but the order must not be changed.

```
<div class="l-footer">

    <!-- Outer layer -->
    <div class="l-footer__wrapper">
        <div class="l-footer__inner">

            <!-- Inner layer -->
            <div class="l-footer__body">
                <div class="l-footer__content">

                </div>
            </div>
        </div>
    </div>
</div>
```

## Container

If a Wrapper is used alongside a Bootstrap container, name it `__container` instead.

```
<div class="l-footer">
    <div class="l-footer__container container">

    </div>
</div>
```

If a Wrapper is still needed as well, the container goes outside the wrapper:

```
<div class="l-footer">
    <div class="l-footer__container container">
        <div class="l-footer__wrapper">

        </div>
    </div>
</div>
```
