import * as React from "react";
import { format, formatDistance } from "date-fns";
import { useHarmonicIntervalFn } from "react-use";

type TimeAgoProps = {
    dateTime: Date | string
    template?: (formattedDateTimeDistance: string) => string
    format?: string
    className?: string
    refreshInterval?: number
}

export default function TimeAgo(props: TimeAgoProps) {
    const dateTime = React.useMemo(
        () => typeof props.dateTime === 'string' ? Date.parse(props.dateTime) : props.dateTime,
        [props.dateTime]
    );
    const formattedDateTime = React.useMemo(
        () => format(dateTime, props.format!), 
        [props.dateTime, props.format]
    );
    const [formattedDateTimeDistance, setFormattedDateTimeDistance] = React.useState(
        props.template!(formatDistance(dateTime, Date.now()))
    );

    useHarmonicIntervalFn(function recalculateDistance() {
        setFormattedDateTimeDistance(
            props.template!(formatDistance(dateTime, Date.now()))
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