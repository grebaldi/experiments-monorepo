import Action from "../action";

import thoughtRepositoryReducer, { ThoughtRepository, thoughtRepository } from "./ThoughtRepository";

export type State = {
    thoughts: ThoughtRepository
}

export const state = {
    thoughts: thoughtRepository
};

export default function stateReducer(state: State, action: Action): State {
    return {
        thoughts: thoughtRepositoryReducer(state.thoughts, action)
    };
}
