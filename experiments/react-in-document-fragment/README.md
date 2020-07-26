# Can React be rendered to a DocumentFragment?

**TL;DR:**
> Yes, but state handling won't work as expected.

## Links

* MDN on DocumentFragment: https://developer.mozilla.org/en-US/docs/Web/API/DocumentFragment
* React DOM: https://reactjs.org/docs/react-dom.html

## Why?

I had a scenario in mind, in which a mechanism for isomorphic rendering written in PHP would prerender React-like components that could be picked up and hydrated with React on the client.

In order to achieve that, I would have to provide an additional server-side rendering mechanism that would render the container element that is necessary to mount components via ReactDOM - I'd like to avoid that, since I do not want another rendering mechanism and I also do not want the trade-offs that come with extra DOM wrapping.

So, I thought about rendering the component tree to a DocumentFragment (which implements the DOM Node Interface) instead of a regular Element Container and then just replace the server-rendered component with the result.

## Rendering A stateful React Component to a DocumentFragment container

The basic setup is this:

```js
function MyComponent() {
    const [val, setVal] = React.useState();
    return (...);
}

const fragment = document.createDocumentFragment();

ReactDOM.render(
    <MyStatefulComponent>
        Hello World
    </MyStatefulComponent>, 
    fragment
);

document.body.appendChild(fragment);
```
*see [src/index.js](./src/index.js) Line 21*

This actually works. The Component renders as expected, but state handling is broken. 

My suspicion is, that it has something to do with this:

> The DocumentFragment interface represents a minimal document object that has **no parent**.
(source: https://developer.mozilla.org/en-US/docs/Web/API/DocumentFragment, emphasis added by me)

Apparently, in order to initialize state handling, ReactDOM needs some sort of parent context, that is simply absent, if you only provide it with a DocumentFragment as a container.

But it gets more interesting than that. 

As soon as you render the stateful component a second time in a regular container, the DocumentFragment-Version also starts to work. Both component trees do not share state, so this does not appear to be a side-effect.

As of right now, I have no explanation for this behavior.

## Alternative 1: Rendering A stateful React Component to an Element with `display: contents;`

A somewhat obvious solution would be to render the component tree into a container, that is styled with `display: contents;`. (For more on that, see: https://developer.mozilla.org/en-US/docs/Web/CSS/display)

The basic setup would be:
```js
function MyComponent() {
    const [val, setVal] = React.useState();
    return (...);
}

const element = document.createElement('div');
element.style.display = 'contents';

ReactDOM.render(
    <MyStatefulComponent>
        Hello World
    </MyStatefulComponent>, 
    element
);
document.body.appendChild(element);
```

In most cases, this will be sufficient. But `display: contents;` has some drawdacks that make it less than ideal:

1. Even though, the promise of `display: contents;` is to be seamless, it still is an extra DOM Node that will affect certain behaviors accordingly (like nth-child count in CSS)
2. Some browsers will remove elements with `display: contents;` from the accessibility tree. This however is buggy behavior that is most likely going to become irrelevant in the feature.

## Alternative 2: Rendering A sateful React Component to a regular DOM Element, but replace the element with its children afterwards

This is a solution I stumbled upon on stackoverflow: https://stackoverflow.com/a/59896351

The basic setup would be:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body>
    <div id="app"></div>
    <script src="main.js"></script>
</body>
</html>
```

```js
function MyComponent() {
    const [val, setVal] = React.useState();
    return (...);

    ReactDOM.render(
        <MyStatefulComponent>
            Hello World
        </MyStatefulComponent>, 
        window.app
    );
    window.app.replaceWith(...window.app.children);
}
```

The only obvious drawback I can see here is that this solution is definitely goind to trigger reflow. But I don't believe that there's a method out there, that would prevent reflow entirely.

Also, even though at a quick glance, state management seems to work fine, I wouldn't be too confident as of right now. 

Nevertheless this approach is the most promising to solve my above stated problem.