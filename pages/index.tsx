import * as React from "react";
import { useObservable } from "react-use";
import { map, distinctUntilChanged, tap } from "rxjs/operators";
import uuid from "uuid";
import { format, formatDistance } from "date-fns";

import {state$, action$} from "../src/domain";
import TimeAgo from "../src/component/TimeAgo";

const now = Date.now();

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

	function handleSubmit() {
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
			<form 
				className="w-3/5 my-5 mx-auto shadow-md bg-white p-2 pb-0"
				onSubmit={event => {
					event.preventDefault();
					handleSubmit();
				}}
				>
				<textarea
					className="block w-full h-24 p-2 bg-gray-100"
					name=""
					id=""
					value={content}
					onChange={event => setContent(event.target.value)}
					onKeyUp={event => {
						if (event.key === 'Enter' && !event.shiftKey) {
							handleSubmit();
						}
					}}
					autoFocus
					/>
				
				<div className="text-center p-5">
					<button className="bg-blue-500 text-white px-5">
						Send
					</button>
				</div>
			</form>

			<h2 className="w-3/5 mx-auto">{thoughts.length} Thoughts</h2>

			<ul className="w-3/5 mx-auto">
				{thoughts.map(thought => (
					<li 
						key={thought.id}
						className="bg-white shadow-md my-3 p-3 relative"
						>
						<TimeAgo
							className="absolute top-0 right-0 p-2 text-xs text-gray-500"
							dateTime={thought.creation_date_time}
							template={f => `${f} ago`}
							/>
						{thought.content}
					</li>
				))}
			</ul>
		</section>
	);
}
