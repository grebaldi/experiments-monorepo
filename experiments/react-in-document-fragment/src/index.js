import React from "react";
import ReactDOM from "react-dom";

function MyStatefulComponent(props) {
    const [count, setCount] = React.useState(0);
    
    return (
        <fieldset>
            <legend>{props.children}</legend>
            <div style={{ display: 'flex' }}>
                <button onClick={() => console.log('setting count') || setCount(count - 1)}>-</button>
                <input type="text" value={count} onChange={e => setCount(Number(e.target.value))}/>
                <button onClick={() => console.log('setting count') || setCount(count + 1)}>+</button>
            </div>

            <h2>Current Count: {count}</h2>
        </fieldset>
    );
}

function initializeFragment() {
    const fragment = document.createDocumentFragment();
    
    ReactDOM.render(
        <MyStatefulComponent>
            Hello World
        </MyStatefulComponent>, 
        fragment
    );
    document.body.appendChild(fragment);
}

function initializeElement() {
    const element = document.createElement('div');
    element.style.display = 'contents';
    
    ReactDOM.render(
        <MyStatefulComponent>
            Hello World
        </MyStatefulComponent>, 
        element
    );
    document.body.appendChild(element);
}

function initializeAppAndReplace() {    
    ReactDOM.render(
        <MyStatefulComponent>
            Hello World
        </MyStatefulComponent>, 
        window.app
    );
    window.app.replaceWith(...window.app.children);
}



// initializeFragment();
// initializeElement();
initializeAppAndReplace();