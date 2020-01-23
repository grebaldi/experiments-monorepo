import { ThoughtWasAdded } from "./ThoughtWasAdded";
import { ThoughtWasRepliedTo } from "./ThoughtWasRepliedTo";
import { ThoughtWasLiked } from "./ThoughtWasLiked";

type Action =
    ThoughtWasAdded |
    ThoughtWasRepliedTo |
    ThoughtWasLiked;

export default Action;