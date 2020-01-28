import { Subject, from } from "rxjs";
import { scan, shareReplay } from "rxjs/operators";
import fetch from "isomorphic-unfetch";

import Action from "./action";
export const action$ = new Subject<Action>();

import stateReducer, { state } from "./reducer";
export async function getInitialState(endpoint: string): Promise<State> {
    const response = await fetch(endpoint);
    const json = await response.json() as Action[];
    const initialState = await from(json).pipe(scan(stateReducer, state)).toPromise();

    return initialState || state;
}
export function getState$(initialState: State) {
    return action$.pipe(
        scan(stateReducer, initialState),
        shareReplay(1)
    );
}
export type State = typeof state;