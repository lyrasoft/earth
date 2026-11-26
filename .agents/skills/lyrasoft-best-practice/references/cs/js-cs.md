# JS / TypeScript Code Style

# 前言

LYRASOFT 以 [AirBnb Javascript Style](https://github.com/airbnb/javascript) 為主要開發規範。

本文件主要針對 AirBnb 標準作額外風格建議，請先熟讀上方的 AirBnb 風格。

# 程式縮排

與設參考 AirBnb 為2格縮進。

在 PHP 專案內時，若是寫在單一 js / ts 檔案內，必須也是 2 格。

但可允許 HTML 內的 inline JS 使用 4 spaces， LYRASOFT 的 PHP 規範以 4 spaces 為縮排，當 JS 不得已必須寫在 PHP / HTML 檔案內時則不在此限，不過應避免混用的情形發生。

## 命名

使用駝峰式命名，但開頭小寫，例如： `fooBar`。

請讓變數命名語意化，易於理解。

```js
var element = document.getElementById('elementId');
```

迴圈中的索引則不在此限，允許使用 `i`, `j`, `k` 作為索引變數。

## 類型檢查

以下提供一些常見的方法來判斷型別。

- 字串: `typeof object === 'string'`
- 數字: `typeof object === 'number'`
- 布林: `typeof object === 'boolean'`
- 物件: `typeof object === 'object'`
- 空白物件: `jQuery.isPlainObject( object )`
- 函式: `jQuery.isFunction( object )`
- 陣列: `jQuery.isArray( object )`
- HTML元素: `object.nodeType`
- null: `object === null`
- null or undefined: `object == null`

**判斷 Undefined:**

- 全域變數: `typeof variable === 'undefined'`
- 區域變數: `variable === undefined`
- 屬性: `object.prop === undefined`

# 類別

## 類別式宣告

若沒有其他特殊需求，統一用 ES class 語法宣告類別

```js
class MyClass {
  foo() {
    //
  }
}

// New instance
const newObject = new MyClass(params);
```

單次使用的 class 可以用靜態宣告

```js
class MyClass {
  static foo() {
    //
  }
}

MyClass.foo();
```

Vue 環境內，優先用 composable 取代 class。

```ts
export function useFoo() {
  const foo = ref(0);

  function increment() {
    foo.value++;
  }

  return { foo, increment };
}
```

# 函式

## 匿名函式

除非有隔離需求，否則匿名函式優先使用箭頭函式

```ts
// 優先
const foo = () => { ... };

// 需要隔離 this 時
const foo = function () {
  console.log(this);
};
```

## 一般函式宣告

除非有必要用變數裝載函數，否則全域函式盡量直接宣告成 function，這樣的好處是可以先使用，後宣告。

```typescript
foo();

function foo() {
  //
}
```

下方是用變數裝載函式，無法後宣告，盡量用在無法正常宣告函式或有需要環境隔離時才這樣做

```typescript
(function () {
  const foo = function () { ... };

  foo();
})();
```


使用變數裝載函式時，。內部的函式會變成匿名函式，可以考慮給他一個名字，除錯時會更容易

```typescript
const foo = function foo() { ... };

console.log(foo); // <-- 這樣可以印出函數名稱
```

# 事件

無論是 JS 還是 jQuery，我們優先用事件名稱優於事件函式或屬性，以下為範例

```js
// Good
el.addEventListener('click', (e) => { ... });
window.addEventListener('load', (e) => { ... });

// Bad
el.onclick = function (e) { ... };
window.onload = (e) => { ... };
```

# 註解

**單行**

- 註解放置在程式碼上方
- 雙斜線與註解之間應空一格

```
// I am a single line comment.
```

**多行註解**

- 多行註解應放置在說明的目標程式碼上方
- 註解起始字元 `/*` 應放在第一行註解上方，註解結束字元 `*/` 應放在最後一行註解下方，
- 每一行註解應加上 1 個星號後綴一個空白

```
/*
 * I am a multiline comment.
 * Line two
 * Line three
 */
```

# 區塊註解 (Docblock)

- 在 TS 環境內，優先用型別宣告取代 JsDoc，除非是純 JS 環境或是需要給 IDE 解析的情況。
- 區塊註解應用於描述函式、物件與變數時。
- 註解起始字元應有兩個星號 `/**` 並放在第一行註解上方，註解結束字元 `*/` 應放在最後一行註解下方。
- 每一行註解應加上 1 個星號，星號前後各加綴一個空白
- 註解最少應包含敘述、參數與回傳值三種內容並依照這個順序排列。若該函式無參數，則可以省略 `@params`。
- 敘述與其他內容之間應該要有一空行

範例：

```js
/**
 * This is my function.
 *
 * @params  {HtmlElement}  html  A html element object.
 *
 * @return  {Object}
 */
```

## 型別宣告

目前我們統一採用 TypeScript，可依照 TypeScript 的要求預先宣告型別

```typescript
// 預宣告型別
const foo: string = str.toString();

// Ionic 專案可能會要求明確的型別不用重複宣告，這是可接受的
let foo = ''; // <-- 會自動推定
let foo: number | string = ''; // <-- 聯合型別必須宣告

// 使用函式提供的泛型參數宣告
const el = document.querySelector<HTMLInputElement>('.foo');
const el = u.selectOne<HTMLInputElement>('.foo');
```


如果是純 JS 環境，可以用 JsDoc 宣告

```js
/** @type {HTMLInputElement} */
const el = document.querySelector('.foo');
```
