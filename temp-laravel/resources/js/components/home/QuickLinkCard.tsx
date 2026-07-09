interface QuickLinkCardProps {
    title: string;
    description: string;
    href: string;
    badge?: string;
}

export function QuickLinkCard({ title, description, href, badge }: QuickLinkCardProps) {
    return (
        <a href={href} className="quick-link-card">
            <div className="quick-link-card-top">
                <h3>{title}</h3>
                {badge ? <span>{badge}</span> : null}
            </div>
            <p>{description}</p>
        </a>
    );
}
