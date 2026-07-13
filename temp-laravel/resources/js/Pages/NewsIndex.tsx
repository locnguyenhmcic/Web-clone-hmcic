import { FormEvent, useEffect, useState } from 'react';
import { EmptyState } from '../components/home/EmptyState';
import { LoadingSkeleton } from '../components/home/LoadingSkeleton';
import { NewsCard } from '../components/home/NewsCard';
import { publicApi } from '../lib/publicApi';
import { PublicWebsiteLayout } from '../layouts/PublicWebsiteLayout';
import type {
    AsyncState,
    CollectionResponse,
    NewsCategory,
    NewsItem,
    SimpleCollectionResponse,
} from '../types/homepage';

function defaultNewsCollection(): CollectionResponse<NewsItem> {
    return {
        data: [],
        pagination: {
            total: 0,
            per_page: 0,
            current_page: 1,
            last_page: 1,
            has_more: false,
        },
    };
}

export default function NewsIndex() {
    const [newsState, setNewsState] = useState<AsyncState<CollectionResponse<NewsItem>>>({
        loading: true,
        data: defaultNewsCollection(),
        error: null,
    });
    const [categoriesState, setCategoriesState] = useState<AsyncState<SimpleCollectionResponse<NewsCategory>>>({
        loading: true,
        data: { data: [] },
        error: null,
    });
    const [searchInput, setSearchInput] = useState('');
    const [searchApplied, setSearchApplied] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');
    const [page, setPage] = useState(1);

    useEffect(() => {
        let active = true;

        publicApi
            .getNewsCategories()
            .then((result) => {
                if (!active) {
                    return;
                }

                setCategoriesState({
                    loading: false,
                    data: result,
                    error: null,
                });
            })
            .catch(() => {
                if (!active) {
                    return;
                }

                setCategoriesState({
                    loading: false,
                    data: { data: [] },
                    error: 'Khong the tai danh muc.',
                });
            });

        return () => {
            active = false;
        };
    }, []);

    useEffect(() => {
        let active = true;

        setNewsState((current) => ({
            ...current,
            loading: true,
            error: null,
        }));

        publicApi
            .getNews(page, 8, {
                categoryId: selectedCategory ? Number(selectedCategory) : undefined,
                search: searchApplied,
            })
            .then((result) => {
                if (!active) {
                    return;
                }

                setNewsState({
                    loading: false,
                    data: result,
                    error: null,
                });
            })
            .catch(() => {
                if (!active) {
                    return;
                }

                setNewsState({
                    loading: false,
                    data: defaultNewsCollection(),
                    error: 'Khong the tai danh sach tin tuc.',
                });
            });

        return () => {
            active = false;
        };
    }, [page, searchApplied, selectedCategory]);

    const handleSearchSubmit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        setPage(1);
        setSearchApplied(searchInput.trim());
    };

    const pagination = newsState.data.pagination;

    return (
        <PublicWebsiteLayout title="Tin tuc - HMCIC">
            <section className="public-page-intro news-index-intro">
                <h1>Tin tuc</h1>
                <p>Cap nhat cac thong bao, bai viet nghiep vu va hoat dong moi nhat tu Trung tam.</p>

                <form className="news-filter-form" onSubmit={handleSearchSubmit}>
                    <input
                        type="search"
                        placeholder="Tim theo tieu de hoac tom tat"
                        value={searchInput}
                        onChange={(event) => setSearchInput(event.target.value)}
                    />

                    <select
                        value={selectedCategory}
                        onChange={(event) => {
                            setSelectedCategory(event.target.value);
                            setPage(1);
                        }}
                        disabled={categoriesState.loading}
                    >
                        <option value="">Tat ca danh muc</option>
                        {categoriesState.data.data.map((category) => (
                            <option key={category.id} value={category.id}>
                                {category.name}
                            </option>
                        ))}
                    </select>

                    <button type="submit">Tim kiem</button>
                </form>

                {categoriesState.error ? <p className="news-filter-error">{categoriesState.error}</p> : null}
            </section>

            <section className="news-index-list" aria-live="polite">
                {newsState.loading ? (
                    <LoadingSkeleton variant="card" count={6} />
                ) : newsState.error ? (
                    <EmptyState title="Khong tai duoc tin tuc" description={newsState.error} />
                ) : newsState.data.data.length === 0 ? (
                    <EmptyState
                        title="Khong co ket qua"
                        description="Thu doi tu khoa tim kiem hoac bo loc danh muc de xem them ket qua."
                    />
                ) : (
                    <>
                        <div className="news-index-grid">
                            {newsState.data.data.map((item) => (
                                <NewsCard key={item.id} item={item} variant="compact" />
                            ))}
                        </div>

                        <div className="news-index-pagination">
                            <button
                                type="button"
                                onClick={() => setPage((current) => Math.max(1, current - 1))}
                                disabled={pagination.current_page <= 1}
                            >
                                Trang truoc
                            </button>

                            <span>
                                Trang {pagination.current_page}/{pagination.last_page}
                            </span>

                            <button
                                type="button"
                                onClick={() => setPage((current) => Math.min(pagination.last_page, current + 1))}
                                disabled={!pagination.has_more}
                            >
                                Trang sau
                            </button>
                        </div>
                    </>
                )}
            </section>
        </PublicWebsiteLayout>
    );
}
