import * as React from "react";

type MyComponentProps = {
    title: string
    description: string
};

function MyComponent(props: MyComponentProps) {
    return (<div>{props.title}</div>);
}

async function prototype<T extends React.ComponentType<any>>(
  component: T,
  props: React.ComponentProps<T>,
) {
  return { component, props };
}

export async function getInitialProps() {
    return {
      myFirstPath: await prototype(MyComponent, {
        title: 'foo',
        description: 'string',
      }),
    };
}
