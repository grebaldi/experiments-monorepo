import { Subject } from "rxjs";
import { scan, tap, shareReplay } from "rxjs/operators";

import Action from "./action";
export const action$ = new Subject<Action>();

import stateReducer, { state } from "./reducer";
export const state$ = action$.pipe(
    scan(stateReducer, state),
    shareReplay(1)
);