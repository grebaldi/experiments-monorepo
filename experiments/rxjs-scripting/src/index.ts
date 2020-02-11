import { from, interval, of, timer, Observable, Subscriber, Operator } from 'rxjs';
import { mergeMap, mapTo, delay, concatMap, buffer, take, delayWhen } from 'rxjs/operators';

type Script<R> = AsyncGenerator<{ text: string }[], R>;

const delayedPromise = <T>(val: T) =>
	new Promise<T>(resolve => {
		setTimeout(() => resolve(val), 1000);
	});

const keypress = async () => {
	process.stdin.setRawMode(true);
	return new Promise(resolve =>
		process.stdin.once('data', () => {
			process.stdin.setRawMode(false);
			resolve();
		})
	);
};
async function* produceMore(): Script<string> {
	yield [{ text: 'Foo' }];
	yield [{ text: 'Bar' }];
	yield [
		...await delayedPromise([{ text: 'Baz' }]),
		...await delayedPromise([{ text: 'Baz' }]),
		...await delayedPromise([{ text: 'Baz' }]),
	];

	return 'yay!';
}

const test = new Observable(subscriber => {
	process.nextTick(() => subscriber.next());
});

async function* script(): Script<void> {
	await keypress();
	yield [{ text: 'Hello' }];
	yield [{ text: 'World' }, { text: '!' }];
	yield[{ text: yield * produceMore() }];
}

export default async function main() {
	const script$: Observable<{ text: string }[]> = Observable.create(
		async (subscriber: Subscriber<{ text: string }[]>) => {
			for await (const res of script()) {
				subscriber.next(res);
			}
		}
	);

	script$.pipe(concatMap(item => of(item).pipe(delay(1000)))).subscribe(function(res) {
		console.clear();
		console.log(
			res.reduce(
				(acc, cur) => ({
					text: [acc.text, cur.text].join(' ').trim()
				}),
				{ text: '' }
			)
		);
	});
}

main();
