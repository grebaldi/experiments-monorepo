import React from "react";
import isArray from "lodash/isArray";

export default function MyPlugin() {
    console.log(isArray([]));
    console.log(React);
    console.log(React.createElement('input'));
}

