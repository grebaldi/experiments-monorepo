import * as React from "react";
import { useState } from "react";
import fetch from "isomorphic-unfetch";

export default function Home() {
	const [latestResponse, setLatestResponse] = useState({});

	return (
		<section>
			<header>
				<h1>Hello World!!!</h1>

				<textarea
					name=""
					id=""
					cols={30}
					rows={10}
					onKeyUp={async event => {
						if (event.key === "Enter") {
							const response = await fetch("/api/echo");
							const json = await response.json();

							setLatestResponse(json);
						}
					}}
				></textarea>
			</header>

			<pre>{JSON.stringify(latestResponse, null, 2)}</pre>
		</section>
	);
}
