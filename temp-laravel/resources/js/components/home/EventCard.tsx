interface EventCardProps {
    title: string;
    date: string;
    href: string;
}

export function EventCard({ title, date, href }: EventCardProps) {
    return (
        <article className="event-card">
            <p>{date}</p>
            <h3>
                <a href={href}>{title}</a>
            </h3>
        </article>
    );
}
