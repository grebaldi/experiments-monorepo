import * as React from "react";

export const structure = {

}

type MyComponentProps = {
    title: React.ReactNode
    description: React.ReactNode
};

export default function MyComponent(props: MyComponentProps) {
    return (
      <div>
        <h2>{props.title}</h2>
        {props.description}
      </div>
    );
}

async function prototype<T extends React.ComponentType<any>>(
  component: T,
  props: React.ComponentProps<T>,
) {
  return { component, props };
}

export async function getInitialProps() {
    return {
      myFirstPath: (
        <MyComponent
          title="Foo"
          description={<MyComponent title="Test" description="Toast" />}
        />
      ),
    };
}
