# JS / TypeScript Code Style

# Introduction

LYRASOFT uses the [Airbnb JavaScript Style Guide](https://github.com/airbnb/javascript) as its primary development standard.

This document adds style recommendations to the Airbnb standard. Read the Airbnb guide above first.

# Code indentation

Follow Airbnb's two-space indentation.

In PHP projects, standalone JS / TS files must also use two spaces.

Four spaces are acceptable for inline JS in HTML. LYRASOFT's PHP convention uses four-space indentation, so this exception applies when JS must be written inside PHP / HTML files; however, avoid mixing these cases.

## Naming

Use lower camelCase, for example: `fooBar`.

Use meaningful variable names that are easy to understand.

```js
var element = document.getElementById('elementId');
```

Loop indexes are an exception; `i`, `j`, and `k` are acceptable index variables.

## Type checking

Here are some common ways to check types:

- String: `typeof object === 'string'`
- Number: `typeof object === 'number'`
- Boolean: `typeof object === 'boolean'`
- Object: `typeof object === 'object'`
- Plain object: `jQuery.isPlainObject( object )`
- Function: `jQuery.isFunction( object )`
- Array: `jQuery.isArray( object )`
- HTML element: `object.nodeType`
- null: `object === null`
- null or undefined: `object == null`

**Checking for undefined:**

- Global variable: `typeof variable === 'undefined'`
- Local variable: `variable === undefined`
- Property: `object.prop === undefined`

# Classes

## Class declarations

Unless there is a specific requirement, always declare classes with ES class syntax.

```js
class MyClass {
  foo() {
    //
  }
}

// New instance
const newObject = new MyClass(params);
```

A class used once can be declared with a static method:

```js
class MyClass {
  static foo() {
    //
  }
}

MyClass.foo();
```

In Vue environments, prefer composables over classes.

```ts
export function useFoo() {
  const foo = ref(0);

  function increment() {
    foo.value++;
  }

  return { foo, increment };
}
```

# Functions

## Anonymous functions

Unless isolation is required, prefer arrow functions for anonymous functions.

```ts
// Preferred
const foo = () => { ... };

// When `this` needs to be isolated
const foo = function () {
  console.log(this);
};
```

## Regular function declarations

Unless a function must be assigned to a variable, declare global functions directly with `function`. This allows them to be used before their declarations.

```typescript
foo();

function foo() {
  //
}
```

The following assigns a function to a variable, so it cannot be used before its declaration. Use this only when a function cannot be declared normally or when environment isolation is needed.

```typescript
(function () {
  const foo = function () { ... };

  foo();
})();
```

When assigning a function to a variable, the inner function becomes anonymous. Consider giving it a name to make debugging easier.

```typescript
const foo = function foo() { ... };

console.log(foo); // <-- This prints the function name
```

# Events

In both JS and jQuery, prefer event names over event handler functions or properties. For example:

```js
// Good
el.addEventListener('click', (e) => { ... });
window.addEventListener('load', (e) => { ... });

// Bad
el.onclick = function (e) { ... };
window.onload = (e) => { ... };
```

# Comments

**Single-line comments**

- Put comments above the code.
- Leave one space between the double slash and the comment.

```
// I am a single line comment.
```

**Multiline comments**

- Put multiline comments above the code they describe.
- Put the opening `/*` on the line before the first comment line and the closing `*/` on the line after the last comment line.
- Prefix each comment line with one asterisk followed by a space.

```
/*
 * I am a multiline comment.
 * Line two
 * Line three
 */
```

# Docblocks

- In TS environments, prefer type declarations over JSDoc, except in pure JS environments or when the IDE needs to parse it.
- Use docblocks to describe functions, objects, and variables.
- Start the block with `/**` on the line above the description and close it with `*/` on the line below the last line.
- Prefix each comment line with one asterisk and a space on either side of the text.
- Include at least a description, parameters, and return value, in that order. Omit `@params` if the function has no parameters.
- Leave a blank line between the description and the other content.

Example:

```js
/**
 * This is my function.
 *
 * @params  {HtmlElement}  html  A html element object.
 *
 * @return  {Object}
 */
```

## Type declarations

We currently standardize on TypeScript; declare types in advance as required by TypeScript.

```typescript
// Declare the type explicitly
const foo: string = str.toString();

// Ionic projects may require an explicit type, but inferring it is also acceptable
let foo = ''; // <-- inferred automatically
let foo: number | string = ''; // <-- union types must be declared

// Declare the type with a function's generic parameter
const el = document.querySelector<HTMLInputElement>('.foo');
const el = u.selectOne<HTMLInputElement>('.foo');
```

In a pure JS environment, use JSDoc to declare the type.

```js
/** @type {HTMLInputElement} */
const el = document.querySelector('.foo');
```
