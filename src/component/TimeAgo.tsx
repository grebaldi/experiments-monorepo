import * as React from "react";
import { format, formatDistance } from "date-fns";
import { useHarmonicIntervalFn } from "react-use";

type TimeAgoProps = {
    dateTime: Date
    template?: (formattedDateTimeDistance: string) => string
    format?: string
    className?: string
    refreshInterval?: number
}

export default function TimeAgo(props: TimeAgoProps) {
    const formattedDateTime = React.useMemo(
        () => format(props.dateTime, props.format!), 
        [props.dateTime, props.format]
    );
    const [formattedDateTimeDistance, setFormattedDateTimeDistance] = React.useState(
        props.template!(formatDistance(props.dateTime, Date.now()))
    );

    useHarmonicIntervalFn(function recalculateDistance() {
        setFormattedDateTimeDistance(
            props.template!(formatDistance(props.dateTime, Date.now()))
        )
    }, props.refreshInterval);

    return (
        <span 
            className={props.className}
            title={formattedDateTime}
            >
            {formattedDateTimeDistance}
        </span>
    )
}

TimeAgo.defaultProps = {
    format: 'PPPppp',
    className: '',
    refreshInterval: 5000,
    template: (f: string) => f
};