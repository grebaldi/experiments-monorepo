import Action from "../action";

export type Thought = {
    id: string
    content: string
    creation_date_time: Date
    number_of_likes: number
    number_of_replies: number
}

export default function thoughtReducer(thought: Thought, action: Action): Thought {
    switch (action.name) {
        case "ThoughtWasRepliedTo":
            return {...thought, number_of_replies: thought.number_of_replies + 1};
        case "ThoughtWasLiked":
            return {...thought, number_of_likes: thought.number_of_likes + 1};
        default: return thought;
    }
}