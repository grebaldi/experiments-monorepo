import React from "react";
import { render } from "react-dom";
import { createTruss } from "./truss";

const Truss = createTruss(r => r.withAddedMiddleware((Component, trace) => {
    if (Component === Button && trace.find(c => c === Header)) {
        return (props: any) => (<span style={{ color: 'red' }}>{props.children}</span>);
    }

    return Component;
}));

function Button(props: React.ButtonHTMLAttributes<HTMLButtonElement>) {
    return (<button {...props}>My Button: {props.children}</button>);
}

function Header(props: React.HTMLAttributes<HTMLElement>) {
    return (<header {...props}>My Header: {props.children}</header>);
}

function Content(props: React.HTMLAttributes<HTMLElement>) {
    return (<div {...props}>My Content: {props.children}</div>);
}

function List(props: React.HTMLAttributes<HTMLElement>) {
    return (<ul {...props}>{props.children}</ul>);
}

function ListItem(props: React.HTMLAttributes<HTMLElement>) {
    return (<li {...props}>{props.children}</li>);
}

function App() {
    return (
        <div>
            <Truss.Element component={Header} props={{}}>
                Test
                <Truss.Element component={Button} props={{}}>
                    Menu
                </Truss.Element>
            </Truss.Element>

            <Truss.Element component={Button} props={{}}>
                Ordinary Button
            </Truss.Element>
        </div>
    );
}

render(
    <Truss.Provider>
        <App/>
    </Truss.Provider>, 
    document.getElementById('app-container')
);