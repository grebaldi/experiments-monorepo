import * as React from "react";
import { useObservable } from "react-use";
import { map, distinctUntilChanged, tap } from "rxjs/operators";
import uuid from "uuid";

import {state$, action$} from "../domain";

export default function Home() {
	const thoughts$ = React.useMemo(() => {
		return state$.pipe(
			map(state => state.thoughts.all),
			distinctUntilChanged(),
			map(thoughts => Object.values(thoughts)),
		);
	}, [1]);
	const thoughts = useObservable(thoughts$, []);
	const [content, setContent] = React.useState<string>('');

	function handleSubmit(event: React.FormEvent) {
		event.preventDefault();

		action$.next({
			name: "ThoughtWasAdded",
			payload: {
				id: uuid.v4(),
				date_time: new Date(),
				content
			}
		});

		setContent('');
	}

	return (
		<section>
			<header>
				<h1>Hello World!!!</h1>

				<form onSubmit={handleSubmit}>
					<textarea
						name=""
						id=""
						cols={30}
						rows={10}
						value={content}
						onChange={event => setContent(event.target.value)}
						/>
					<button>Send</button>
				</form>
			</header>

			<h2>{thoughts.length} Thoughts</h2>

			<ul>
				{thoughts.map(thought => (
					<li key={thought.id}>{thought.content}</li>
				))}
			</ul>
		</section>
	);
}
