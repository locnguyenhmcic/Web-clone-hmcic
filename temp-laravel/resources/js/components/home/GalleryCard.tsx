interface GalleryCardProps {
    title: string;
    imageUrl: string | null;
    href: string;
}

export function GalleryCard({ title, imageUrl, href }: GalleryCardProps) {
    return (
        <article className="gallery-card">
            <a href={href} className="gallery-card-media">
                {imageUrl ? <img src={imageUrl} alt={title} loading="lazy" /> : <div>No image</div>}
            </a>
            <h3>
                <a href={href}>{title}</a>
            </h3>
        </article>
    );
}
