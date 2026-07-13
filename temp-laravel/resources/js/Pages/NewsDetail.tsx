import { useEffect, useState } from 'react';
import { EmptyState } from '../components/home/EmptyState';
import { LoadingSkeleton } from '../components/home/LoadingSkeleton';
import { NewsCard } from '../components/home/NewsCard';
import { SectionFrame } from '../components/home/SectionFrame';
import { publicApi } from '../lib/publicApi';
import { PublicWebsiteLayout } from '../layouts/PublicWebsiteLayout';
import type { AsyncState, FoundationFlags, NewsDetailItem } from '../types/homepage';

interface NewsDetailProps {
    foundation: FoundationFlags;
    slug: string;
}

export default function NewsDetail({ slug }: NewsDetailProps) {
    const [detailState, setDetailState] = useState<AsyncState<NewsDetailItem | null>>({
        loading: true,
        data: null,
        error: null,
    });

    useEffect(() => {
        let active = true;

        setDetailState({ loading: true, data: null, error: null });

        publicApi
            .getNewsDetail(slug)
            .then((result) => {
                if (!active) {
                    return;
                }

                setDetailState({
                    loading: false,
                    data: result.data,
                    error: null,
                });
            })
            .catch(() => {
                if (!active) {
                    return;
                }

                setDetailState({
                    loading: false,
                    data: null,
                    error: 'Khong the tai chi tiet bai viet hoac bai viet khong ton tai.',
                });
            });

        return () => {
            active = false;
        };
    }, [slug]);

    const article = detailState.data;

    return (
        <PublicWebsiteLayout title={article?.title || 'Chi tiet tin tuc'}>
            <nav className="news-breadcrumb" aria-label="Breadcrumb">
                <a href="/">Trang chu</a>
                <span>/</span>
                <a href="/tin-tuc">Tin tuc</a>
                {article ? (
                    <>
                        <span>/</span>
                        <span>{article.title}</span>
                    </>
                ) : null}
            </nav>

            {detailState.loading ? (
                <LoadingSkeleton variant="hero" count={1} />
            ) : detailState.error || !article ? (
                <EmptyState
                    title="Khong tai duoc bai viet"
                    description={detailState.error || 'Noi dung chi tiet hien chua san sang.'}
                />
            ) : (
                <>
                    <article className="news-detail-shell">
                        <header className="news-detail-header">
                            <p className="news-card-meta">
                                <span>{article.category || 'Tin tuc'}</span>
                                <span>{article.created_at || 'Dang cap nhat ngay dang'}</span>
                            </p>
                            <h1>{article.title}</h1>
                            {article.excerpt ? <p className="news-detail-excerpt">{article.excerpt}</p> : null}
                        </header>

                        {article.thumbnail_url ? (
                            <div className="news-detail-cover">
                                <img src={article.thumbnail_url} alt={article.title} loading="eager" />
                            </div>
                        ) : null}

                        <div
                            className="news-detail-content"
                            dangerouslySetInnerHTML={{ __html: article.content }}
                        />
                    </article>

                    <SectionFrame title="Bai viet lien quan" subtitle="Cac bai viet cung nhom noi dung">
                        {article.related_posts.length === 0 ? (
                            <EmptyState
                                title="Chua co bai viet lien quan"
                                description="Danh sach bai viet lien quan se duoc cap nhat sau."
                            />
                        ) : (
                            <div className="news-index-grid">
                                {article.related_posts.map((item) => (
                                    <NewsCard key={item.id} item={item} variant="compact" />
                                ))}
                            </div>
                        )}
                    </SectionFrame>
                </>
            )}
        </PublicWebsiteLayout>
    );
}