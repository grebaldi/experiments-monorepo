export type ThoughtWasAdded = {
    name: 'ThoughtWasAdded'
    payload: {
        id: string
        date_time: Date
        content: string
    }
}

