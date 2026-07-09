interface VideoCardProps {
    title: string;
    href: string;
}

export function VideoCard({ title, href }: VideoCardProps) {
    return (
        <article className="video-card">
            <div className="video-card-thumb">Video</div>
            <h3>
                <a href={href}>{title}</a>
            </h3>
        </article>
    );
}
