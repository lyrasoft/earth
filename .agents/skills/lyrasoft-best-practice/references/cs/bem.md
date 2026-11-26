# CSS BEM 概念與規範

# 適用範圍

當專案採用傳統 CSS 或 SCSS，屬於 Bootstrap 搭配手寫 CSS 的專案，則使用 BEM 來命名 class。

若當下的環境是 Taiwindcss 或其他 CSS-in-JS 的專案，則不適用 BEM。

# 基本概念

BEM 基本概念

- Block `.block`
- Element `.block__element`
- Modifier `.block__element--modifier`

用這三個層次來組合 HTML class 結構

若單字較長，用單一 `-` 區隔，例如 `main-block` 視為一個 block name

## 什麼時候用 block

任何一個功能明確的大區塊都可以用 block 開頭，block 下面可以有更多 block 且不必有相依關係。

## 什麼時候用 element

block 區塊內的內容元素都可以用 element 來區隔其目的

例如

```html
<div class="card">
  <div class="card__header">
  
  </div>
  
  <div class="card__body">
  
  </div>
</div>
```

## 什麼時候用 Modifier

相同的元素但是有狀態或類型區隔的時候，可與 block 或 element class 在同一個元素上

（block 與 element）不能存在同一個元素

狀態範例

```
<a href="#" class="c-button c-button--active"></a>
```

類型範例

```
<button class="c-button c-button--primary"></button>
```


# 不要巢狀 element

要儘量維持扁平的層次，例如以下是不建議的寫法

```
<div class="card">
    <div class="card__header">
        <h2 class="card__header__title">
            Hello
        </div>
    </div>
</div>
```

建議的寫法

```
<div class="card">
    <div class="card__header">
        <h2 class="card__title">
            Hello
        </div>
    </div>
</div>
```

# 何時切分新的 block

沒有固定標準，但是若有必要，可以用較長的名稱做下一層 block，總之儘量不要出現雙層 element

```
<div class="header">
    <ul class="header-menu">
        <li class="header-menu__link">
            Link Text
        </li>
    </ul>
</div>
```

也可以考慮把某個 element 視為下一層的新 block

```
<div class="header">
    <ul class="header__menu menu">
        <li class="menu__link">
            Link Text
        </li>
    </ul>
</div>
```

若擔心 block 命名容易衝突，可以參考後面的 Namespace

## 不要跨 block 使用 element

不要讓兩個 block 的層級交錯

不好

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

好

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

## 不要向下伸進迴圈

這樣會造成頁面重購時的困難

不好

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

好

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

我們可以用 Namespace 簡寫，來快速區隔 block 的屬性，例如以下常用 Namespace

| Type | Prefix | Examples | Description |
| --- | --- | --- | --- |
| Component | `c-` | `c-card` `c-checklist` | 某個元件或重要區塊。Component 的意思就是一個獨立的封裝小工具，例如 button, link, card, input 等等。可能會被重複使用，但並非一定要重複使用。 |
| Layout | `l-` | `l-grid` `l-product-page` | 版面、排版相關。大多數時候不會重複使用，單純用來切割版面各個區塊用的 |
| Helper | `h-` | `h-show` `h-hide` | 用來快速加上 css 樣式的幫手類別 |
| States | `is-` `has-` | `is-active` `has-loaded` | 表現狀態差異用 |
| Javascript Hooks | `js-` | `js-select2` | 專門給 JS 或 jQuery 抓取的 class |


一個元素也可以同時屬於 Layout 與 Component，例如

```
<div class="l-product-intro c-product-intro"
```


## 一個較為完整的範例

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


如果要弄清楚什麼是 Component & Layout，可以看這篇文章
[\[前端\] 什麼是 Component & Layout](https://lyrasoft.atlassian.net/wiki/spaces/MEG/pages/1684701573/Component+Layout)


# 層次與順序

由於採用 BEM ，block 名稱一定要在最上層。而寫 HTML 時有時候需要多個層次來製作樣式效果，因此我們規範一些常見的命名規則讓大家定的層次一致

## Wrapper

當 block 下面需要一個用來指定間距的內部元素時，我們以 `__wrapper` 命名，其次 `__inner`


若層次較多，可以再加一個 `__body` 用在下層包裹主要元素，也可以再最內層用 `__content` 來包裹實際內容，故其順序會是這樣，不一定每層都要，但順序不能錯亂。

```
<div class="l-footer">

    <!-- 外層 -->
    <div class="l-footer__wrapper">
        <div class="l-footer__inner">

            <!-- 內層 -->
            <div class="l-footer__body">
                <div class="l-footer__content">

                </div>
            </div>
        </div>
    </div>
</div>
```


## Container

若 Wrapper 會與 Bootstrap container 同時用的時候，則改叫 `__container`


```
<div class="l-footer">
    <div class="l-footer__container container">

    </div>
</div>
```

若同時還是需要 Wrapper，則 container 大於 wrapper


```
<div class="l-footer">
    <div class="l-footer__container container">
        <div class="l-footer__wrapper">

        </div>
    </div>
</div>
```

