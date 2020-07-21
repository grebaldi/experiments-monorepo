import React, { useContext } from "react";

class Registry {
    constructor(
        private middlewares: Middleware[] = [],
        private trace: Trace = []
    ) {}

    public withAddedMiddleware(middleware: Middleware): Registry {
        return new Registry([...this.middlewares, middleware], this.trace);
    }

    public withAddedTraceLevel(component: React.ComponentType) {
        return new Registry(this.middlewares, [...this.trace, component]);
    }

    public getElement<P>(component: React.ComponentType<P>): React.ComponentType<P> {
        return this.middlewares.reduce((acc, cur) => cur(acc, this.trace), component);
    }
}

type Trace = React.ComponentType[];
type Middleware<P = any> = (type: React.ComponentType<P>, trace: Trace) => React.ComponentType<P>

interface ElementProps<P> {
    component: React.ComponentType<P>
    props: P
    children?: React.ReactNode
}

export function createTruss(init?: (registry: Registry) => Registry) {
    const registry = init ? init(new Registry()) : new Registry();
    const Context = React.createContext(registry);

    function Provider(props: { children: React.ReactNode, value?: Registry }) {
        return (
            <Context.Provider value={props.value || registry}>
                {props.children}
            </Context.Provider>
        );
    }

    function Element<P>(props: ElementProps<P>) {
        const context = useContext(Context);
        const Component = context.getElement(props.component);
    
        return (
            <Context.Provider value={context.withAddedTraceLevel(Component)}>
                <Component {...props.props}>
                    {props.children}
                </Component>
            </Context.Provider>
        );
    }

    return { Provider, Element };
}