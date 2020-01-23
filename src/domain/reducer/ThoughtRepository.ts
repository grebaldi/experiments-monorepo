import Action from "../action";

import thoughtReducer, { Thought } from "./Tought";

export type ThoughtRepository = {
    all: { [id: string]: Thought }
    replies: { [id: string]: Thought[] }
};

export const thoughtRepository: ThoughtRepository = {
    all: {},
    replies: {}
};

export default function thoughtRepositoryReducer(thoughtRepository: ThoughtRepository, action: Action): ThoughtRepository {
    switch (action.name) {
        case "ThoughtWasAdded":
            return {
                ...thoughtRepository, 
                all: {
                    ...thoughtRepository.all,
                    [action.payload.id]: {
                        id: action.payload.id, 
                        content: action.payload.content,
                        creation_date_time: action.payload.date_time,
                        number_of_likes: 0,
                        number_of_replies: 0
                    }
                }
            };    

        case "ThoughtWasLiked":
            return {
                ...thoughtRepository, 
                all: {
                    ...thoughtRepository.all,
                    [action.payload.id]: thoughtReducer(thoughtRepository.all[action.payload.id], action)
                }
            };
        
        case "ThoughtWasRepliedTo":
            return {
                all: {
                    ...thoughtRepository.all,
                    [action.payload.id]: {
                        id: action.payload.id, 
                        content: action.payload.content,
                        creation_date_time: action.payload.date_time,
                        number_of_likes: 0,
                        number_of_replies: 0
                    }
                },
                replies: {
                    ...thoughtRepository.replies,
                    [action.payload.original_id]: [
                        ...(thoughtRepository.replies[action.payload.original_id] || []),
                        {
                            id: action.payload.id, 
                            content: action.payload.content,
                            creation_date_time: action.payload.date_time,
                            number_of_likes: 0,
                            number_of_replies: 0
                        }
                    ]
                }
            }
        
        default: return thoughtRepository;
    }
}