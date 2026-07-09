import { useEffect, useMemo, useState } from 'react';
import { CategorySection } from '../components/home/CategorySection';
import { EmptyState } from '../components/home/EmptyState';
import { EventCard } from '../components/home/EventCard';
import { GalleryCard } from '../components/home/GalleryCard';
import { HeroSection } from '../components/home/HeroSection';
import { LoadingSkeleton } from '../components/home/LoadingSkeleton';
import { NewsCard } from '../components/home/NewsCard';
import { QuickLinkCard } from '../components/home/QuickLinkCard';
import { SectionFrame } from '../components/home/SectionFrame';
import { SidebarWidget } from '../components/home/SidebarWidget';
import { StatisticsCard } from '../components/home/StatisticsCard';
import { VideoCard } from '../components/home/VideoCard';
import { publicApi } from '../lib/publicApi';
import { PublicWebsiteLayout } from '../layouts/PublicWebsiteLayout';
import type { AsyncState, CollectionResponse, FaqItem, FoundationFlags, NewsItem, ProjectItem, ServiceItem, SimpleCollectionResponse } from '../types/homepage';

interface HomeProps {
    foundation: FoundationFlags;
}

function formatDate(value: string): string {
    if (!value) {
        return 'N/A';
    }

    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? value : date.toLocaleDateString('vi-VN');
}

function defaultCollection<T>(): CollectionResponse<T> {
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

export default function Home({ foundation }: HomeProps) {
    const [newsState, setNewsState] = useState<AsyncState<CollectionResponse<NewsItem>>>({
        loading: true,
        data: defaultCollection<NewsItem>(),
        error: null,
    });
    const [projectState, setProjectState] = useState<AsyncState<CollectionResponse<ProjectItem>>>({
        loading: true,
        data: defaultCollection<ProjectItem>(),
        error: null,
    });
    const [serviceState, setServiceState] = useState<AsyncState<SimpleCollectionResponse<ServiceItem>>>({
        loading: true,
        data: { data: [] },
        error: null,
    });
    const [faqState, setFaqState] = useState<AsyncState<SimpleCollectionResponse<FaqItem>>>({
        loading: true,
        data: { data: [] },
        error: null,
    });

    useEffect(() => {
        let isMounted = true;

        const loadHomepageData = async () => {
            const [newsResult, projectResult, serviceResult, faqResult] = await Promise.allSettled([
                publicApi.getNews(1, 8),
                publicApi.getProjects(1, 8, true),
                publicApi.getServices(),
                publicApi.getFaq(),
            ]);

            if (!isMounted) {
                return;
            }

            if (newsResult.status === 'fulfilled') {
                setNewsState({ loading: false, data: newsResult.value, error: null });
            } else {
                setNewsState({ loading: false, data: defaultCollection<NewsItem>(), error: 'Khong the tai tin tuc.' });
            }

            if (projectResult.status === 'fulfilled') {
                setProjectState({ loading: false, data: projectResult.value, error: null });
            } else {
                setProjectState({ loading: false, data: defaultCollection<ProjectItem>(), error: 'Khong the tai du an.' });
            }

            if (serviceResult.status === 'fulfilled') {
                setServiceState({ loading: false, data: serviceResult.value, error: null });
            } else {
                setServiceState({ loading: false, data: { data: [] }, error: 'Khong the tai dich vu.' });
            }

            if (faqResult.status === 'fulfilled') {
                setFaqState({ loading: false, data: faqResult.value, error: null });
            } else {
                setFaqState({ loading: false, data: { data: [] }, error: 'Khong the tai module thong bao.' });
            }
        };

        loadHomepageData();

        return () => {
            isMounted = false;
        };
    }, []);

    const featuredNews = newsState.data.data[0];
    const recentNews = newsState.data.data.slice(1, 5);
    const galleryItems = projectState.data.data.slice(0, 4);

    const categoryItems = useMemo(() => {
        const map = new Map<string, number>();

        newsState.data.data.forEach((news) => {
            const key = news.category || 'Khac';
            map.set(key, (map.get(key) || 0) + 1);
        });

        return Array.from(map.entries()).map(([name, count]) => ({ name, count }));
    }, [newsState.data.data]);

    const notifications = useMemo(() => {
        if (faqState.data.data.length > 0) {
            return faqState.data.data.slice(0, 5).map((item, index) => ({
                title: item.title || item.question || `Thong bao ${index + 1}`,
                href: item.slug ? `/hoi-dap/${item.slug}` : '/hoi-dap',
            }));
        }

        return newsState.data.data.slice(0, 5).map((item) => ({
            title: item.title,
            href: `/tin-tuc/${item.slug}`,
        }));
    }, [faqState.data.data, newsState.data.data]);

    const eventItems = newsState.data.data.slice(0, 3).map((item) => ({
        title: item.title,
        date: formatDate(item.created_at),
        href: `/tin-tuc/${item.slug}`,
    }));

    const statistics = [
        { label: 'Tong bai viet', value: newsState.data.pagination.total.toString() },
        { label: 'Du an tieu bieu', value: projectState.data.pagination.total.toString() },
        { label: 'Nhom dich vu', value: serviceState.data.data.length.toString() },
        { label: 'Phien ban giao dien', value: foundation.version },
    ];

    return (
        <PublicWebsiteLayout title="Trang chu">
            <HeroSection
                title="Cong thong tin dien tu Trung tam Quan ly nha va Giam dinh xay dung"
                description="Cap nhat thong tin chinh thong, thong bao nghiep vu, du an tieu bieu va cac dich vu cong trong linh vuc xay dung."
            />

            <SectionFrame
                title="Lien ket nhanh"
                subtitle="Truy cap nhanh cac nhom thong tin va dich vu trong mot thao tac"
            >
                <div className="quick-link-grid">
                    <QuickLinkCard title="Gioi thieu" description="Tong quan, so do to chuc, linh vuc hoat dong" href="/gioi-thieu" />
                    <QuickLinkCard title="Tin tuc" description="Tin thong bao, tuyen dung, mua sam" href="/tin-tuc" badge="Moi" />
                    <QuickLinkCard title="Du an" description="Danh muc du an tieu bieu da thuc hien" href="/du-an" />
                    <QuickLinkCard title="Lien he" description="Thong tin lien he va kenh tiep nhan" href="/lien-he" />
                </div>
            </SectionFrame>

            <SectionFrame
                title="Du an tieu bieu"
                subtitle="Cac cong trinh noi bat duoc thuc hien boi Trung tam"
                actionLabel="Xem tat ca"
                actionHref="/du-an"
            >
                {projectState.loading ? (
                    <LoadingSkeleton variant="card" count={4} />
                ) : projectState.error ? (
                    <EmptyState title="Khong tai duoc du lieu" description={projectState.error} />
                ) : galleryItems.length === 0 ? (
                    <EmptyState title="Chua co du an" description="Du lieu du an se duoc cap nhat trong thoi gian toi." />
                ) : (
                    <div className="gallery-grid">
                        {galleryItems.map((project) => (
                            <GalleryCard
                                key={project.id}
                                title={project.title}
                                imageUrl={project.thumbnail_url}
                                href={`/du-an/${project.slug}`}
                            />
                        ))}
                    </div>
                )}
            </SectionFrame>

            <SectionFrame
                title="Tin tuc va thong bao"
                subtitle="Thong tin cap nhat moi nhat tu Trung tam"
                actionLabel="Xem toan bo"
                actionHref="/tin-tuc"
            >
                <div className="news-hub-grid">
                    <div className="news-hub-main">
                        {newsState.loading ? (
                            <LoadingSkeleton variant="hero" count={1} />
                        ) : newsState.error ? (
                            <EmptyState title="Khong tai duoc tin tuc" description={newsState.error} />
                        ) : !featuredNews ? (
                            <EmptyState title="Chua co tin tuc" description="Tin tuc se duoc cap nhat trong thoi gian toi." />
                        ) : (
                            <>
                                <NewsCard item={featuredNews} variant="featured" />
                                <div className="news-compact-grid">
                                    {recentNews.map((item) => (
                                        <NewsCard key={item.id} item={item} variant="compact" />
                                    ))}
                                </div>
                            </>
                        )}
                    </div>

                    <div className="news-hub-sidebar">
                        {faqState.loading && notifications.length === 0 ? (
                            <LoadingSkeleton variant="list" count={1} />
                        ) : notifications.length > 0 ? (
                            <SidebarWidget title="Thong bao" items={notifications} />
                        ) : (
                            <EmptyState title="Chua co thong bao" description="Thong bao moi se xuat hien tai day." />
                        )}

                        <div className="event-list-grid">
                            {eventItems.length === 0 ? (
                                <EmptyState title="Chua co su kien" description="Noi dung su kien se duoc cap nhat sau." />
                            ) : (
                                eventItems.map((event) => (
                                    <EventCard key={event.href} title={event.title} date={event.date} href={event.href} />
                                ))
                            )}
                        </div>
                    </div>
                </div>
            </SectionFrame>

            <SectionFrame
                title="Danh muc tin"
                subtitle="Tong hop nhom tin theo chu de"
            >
                {newsState.loading ? (
                    <LoadingSkeleton variant="card" count={4} />
                ) : categoryItems.length === 0 ? (
                    <EmptyState title="Khong co chuyen muc" description="Danh muc se hien thi khi co bai viet tuong ung." />
                ) : (
                    <CategorySection items={categoryItems} />
                )}
            </SectionFrame>

            <SectionFrame
                title="Dich vu cong"
                subtitle="Cac nhom dich vu va chuc nang dang duoc cung cap"
            >
                {serviceState.loading ? (
                    <LoadingSkeleton variant="card" count={6} />
                ) : serviceState.error ? (
                    <EmptyState title="Khong tai duoc dich vu" description={serviceState.error} />
                ) : serviceState.data.data.length === 0 ? (
                    <EmptyState title="Chua co dich vu" description="Danh muc dich vu se duoc cap nhat som." />
                ) : (
                    <div className="service-grid">
                        {serviceState.data.data.map((service) => (
                            <QuickLinkCard
                                key={service.id}
                                title={service.name}
                                description="Truy cap nhom thong tin va tai lieu lien quan"
                                href={`/dich-vu/${service.slug}`}
                            />
                        ))}
                    </div>
                )}
            </SectionFrame>

            <SectionFrame
                title="Thong ke nhanh"
                subtitle="Tong quan du lieu hien thi tren cong thong tin"
            >
                <div className="statistics-grid">
                    {statistics.map((item) => (
                        <StatisticsCard key={item.label} label={item.label} value={item.value} />
                    ))}
                </div>
            </SectionFrame>

            <SectionFrame
                title="Truyen thong"
                subtitle="Video va album anh"
            >
                <div className="media-grid">
                    <div>
                        <h3 className="media-subtitle">Video</h3>
                        <div className="video-grid">
                            <VideoCard title="Gioi thieu ve Trung tam" href="/tin-tuc" />
                            <VideoCard title="Huong dan tra cuu thong tin" href="/tin-tuc" />
                        </div>
                    </div>
                    <div>
                        <h3 className="media-subtitle">Album anh</h3>
                        {galleryItems.length === 0 ? (
                            <EmptyState title="Chua co album" description="Album anh se duoc cap nhat tu nguon du an." />
                        ) : (
                            <div className="gallery-grid gallery-grid-compact">
                                {galleryItems.slice(0, 2).map((item) => (
                                    <GalleryCard
                                        key={`media-${item.id}`}
                                        title={item.title}
                                        imageUrl={item.thumbnail_url}
                                        href={`/du-an/${item.slug}`}
                                    />
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </SectionFrame>

            <SectionFrame
                title="Khach hang - Doi tac"
                subtitle="Mang luoi don vi lien ket"
            >
                <div className="partner-grid">
                    <article>SXDV TP.HCM</article>
                    <article>He thong dich vu cong</article>
                    <article>Cong thong tin xay dung</article>
                    <article>Don vi nghien cuu chuyen nganh</article>
                </div>
            </SectionFrame>
        </PublicWebsiteLayout>
    );
}
