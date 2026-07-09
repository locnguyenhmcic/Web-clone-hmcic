import type { NewsItem } from '../../types/homepage';

interface NewsCardProps {
    item: NewsItem;
    variant?: 'featured' | 'compact';
}

function formatDate(value: string): string {
    if (!value) {
        return 'N/A';
    }

    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? value : date.toLocaleDateString('vi-VN');
}

export function NewsCard({ item, variant = 'compact' }: NewsCardProps) {
    return (
        <article className={`news-card news-card-${variant}`}>
            <a href={`/tin-tuc/${item.slug}`} className="news-card-media" aria-label={item.title}>
                {item.thumbnail_url ? (
                    <img src={item.thumbnail_url} alt={item.title} loading="lazy" />
                ) : (
                    <div className="news-card-fallback">No image</div>
                )}
            </a>
            <div className="news-card-body">
                <p className="news-card-meta">
                    <span>{item.category || 'Tin tuc'}</span>
                    <span>{formatDate(item.created_at)}</span>
                </p>
                <h3>
                    <a href={`/tin-tuc/${item.slug}`}>{item.title}</a>
                </h3>
                <p>{item.excerpt || 'Dang cap nhat noi dung.'}</p>
            </div>
        </article>
    );
}
