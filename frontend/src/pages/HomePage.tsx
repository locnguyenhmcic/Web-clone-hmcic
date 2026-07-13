'use client';

import { useEffect, useMemo, useState } from 'react';
import { backendApiClient } from '@/shared/api/backendApiClient';
import { usePagination } from '@/shared/hooks/usePagination';
import { PaginationControls } from '@/shared/components/PaginationControls';
import { ProjectCard } from '@/features/project/components/ProjectCard';
import { NewsCard } from '@/features/news/components/NewsCard';
import { LazyImage } from '@/shared/components/LazyImage';

interface Project {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  thumbnail_url: string | null;
  created_at: string;
  featured: boolean;
}

interface News {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  thumbnail_url: string | null;
  category: string | null;
  created_at: string;
}

interface Service {
  id: number;
  name: string;
  slug: string;
}

const QUICK_LINKS = [
  { title: 'Tin tức', href: '/tin-tuc', description: 'Cập nhật thông báo và văn bản mới.' },
  { title: 'Dự án', href: '/du-an', description: 'Xem các dự án tiêu biểu đã triển khai.' },
  { title: 'Dịch vụ', href: '/dich-vu', description: 'Tra cứu danh mục dịch vụ chuyên ngành.' },
  { title: 'Liên hệ', href: '/lien-he', description: 'Gửi yêu cầu hỗ trợ nhanh cho trung tâm.' },
];

export function HomePage() {
  const newsState = usePagination(6);
  const [newsList, setNewsList] = useState<News[]>([]);
  const [newsLoading, setNewsLoading] = useState(false);

  const projectsState = usePagination(6);
  const [projectsList, setProjectsList] = useState<Project[]>([]);
  const [projectsLoading, setProjectsLoading] = useState(false);

  const [servicesList, setServicesList] = useState<Service[]>([]);
  const [servicesLoading, setServicesLoading] = useState(false);

  const [selectedCategory, setSelectedCategory] = useState<string>('Tất cả');

  useEffect(() => {
    const fetchNews = async () => {
      setNewsLoading(true);
      try {
        const data = await backendApiClient.getNews(newsState.currentPage, 7);
        setNewsList(data.data || []);
        newsState.updatePagination({
          total: data.pagination.total,
          lastPage: data.pagination.last_page,
          hasMore: data.pagination.has_more,
        });
      } catch (error) {
        console.error('Failed to fetch news:', error);
      } finally {
        setNewsLoading(false);
      }
    };

    fetchNews();
  }, [newsState.currentPage]);

  useEffect(() => {
    const fetchProjects = async () => {
      setProjectsLoading(true);
      try {
        const data = await backendApiClient.getProjects(projectsState.currentPage, 6, true);
        setProjectsList(data.data || []);
        projectsState.updatePagination({
          total: data.pagination.total,
          lastPage: data.pagination.last_page,
          hasMore: data.pagination.has_more,
        });
      } catch (error) {
        console.error('Failed to fetch projects:', error);
      } finally {
        setProjectsLoading(false);
      }
    };

    fetchProjects();
  }, [projectsState.currentPage]);

  useEffect(() => {
    const fetchServices = async () => {
      setServicesLoading(true);
      try {
        const data = await backendApiClient.getServices();
        setServicesList(data.data || []);
      } catch (error) {
        console.error('Failed to fetch services:', error);
      } finally {
        setServicesLoading(false);
      }
    };

    fetchServices();
  }, []);

  const featuredNews = newsList[0] ?? null;
  const latestNews = newsList.slice(1);

  const categoryOptions = useMemo(() => {
    const categories = new Set<string>();
    newsList.forEach((item) => {
      if (item.category && item.category.trim().length > 0) {
        categories.add(item.category.trim());
      }
    });
    return ['Tất cả', ...Array.from(categories)];
  }, [newsList]);

  const filteredLatestNews = useMemo(() => {
    if (selectedCategory === 'Tất cả') {
      return latestNews;
    }
    return latestNews.filter((item) => item.category === selectedCategory);
  }, [latestNews, selectedCategory]);

  return (
    <main className="min-h-screen bg-white">
      <section className="bg-gradient-to-r from-blue-700 to-blue-900 text-white py-16 px-4">
        <div className="max-w-6xl mx-auto grid gap-8 lg:grid-cols-[1.2fr_0.8fr] items-center">
          <div>
            <p className="text-sm uppercase tracking-[0.14em] opacity-80 mb-3">Cổng thông tin công</p>
            <h1 className="text-4xl md:text-5xl font-bold leading-tight mb-4">
              Trung tâm Quản lý Nhà và Giám định Xây dựng TP.HCM
            </h1>
            <p className="text-lg opacity-90 mb-8 max-w-2xl">
              Cung cấp dịch vụ quản lý nhà, giám định chất lượng công trình và tư vấn chuyên ngành
              cho tổ chức, doanh nghiệp và người dân.
            </p>
            <div className="flex flex-wrap gap-3">
              <a
                href="/dich-vu"
                className="inline-flex items-center justify-center bg-white text-blue-700 font-semibold py-3 px-8 rounded-md hover:bg-gray-100 transition"
              >
                Tra cứu dịch vụ
              </a>
              <a
                href="/tin-tuc"
                className="inline-flex items-center justify-center border border-white/50 text-white font-semibold py-3 px-8 rounded-md hover:bg-white/10 transition"
              >
                Xem tin tức
              </a>
            </div>
          </div>

          <aside className="bg-white/10 border border-white/20 rounded-lg p-6 backdrop-blur-sm">
            <h2 className="text-xl font-semibold mb-4">Thông tin nhanh</h2>
            <ul className="space-y-3 text-sm">
              <li className="flex items-center justify-between"><span>Dự án đang theo dõi</span><strong>127</strong></li>
              <li className="flex items-center justify-between"><span>Dịch vụ công trực tuyến</span><strong>25</strong></li>
              <li className="flex items-center justify-between"><span>Tỷ lệ xử lý đúng hạn</span><strong>98.7%</strong></li>
            </ul>
          </aside>
        </div>
      </section>

      <section className="py-14 px-4 border-b">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">Tin nổi bật</h2>
          <p className="text-gray-600 mb-8">Thông tin quan trọng được ưu tiên hiển thị</p>

          {newsLoading ? (
            <div className="text-center py-12">Đang tải dữ liệu...</div>
          ) : featuredNews ? (
            <article className="grid gap-6 lg:grid-cols-[1fr_1.2fr] bg-blue-50 border border-blue-100 rounded-lg p-5">
              {featuredNews.thumbnail_url ? (
                <LazyImage
                  src={featuredNews.thumbnail_url}
                  alt={featuredNews.title}
                  className="w-full h-64 object-cover rounded-md"
                  width={800}
                  height={512}
                />
              ) : (
                <div className="w-full h-64 rounded-md bg-blue-100" />
              )}

              <div>
                <span className="inline-flex text-xs font-semibold bg-blue-600 text-white rounded px-2.5 py-1 mb-3">
                  Nổi bật
                </span>
                <h3 className="text-2xl font-bold mb-3">{featuredNews.title}</h3>
                <p className="text-gray-700 mb-4">{featuredNews.excerpt}</p>
                <div className="flex items-center gap-3 text-sm text-gray-600">
                  <span>{featuredNews.created_at}</span>
                  {featuredNews.category && <span className="px-2 py-1 rounded bg-white border">{featuredNews.category}</span>}
                </div>
                <a
                  href={`/tin-tuc/${featuredNews.slug}`}
                  className="inline-flex mt-5 text-blue-700 font-semibold hover:underline"
                >
                  Đọc chi tiết
                </a>
              </div>
            </article>
          ) : (
            <div className="text-center py-12 text-gray-500">Chưa có tin nổi bật</div>
          )}
        </div>
      </section>

      <section className="py-14 px-4 bg-gray-50 border-b">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">Chuyên mục</h2>
          <p className="text-gray-600 mb-6">Lọc nhanh tin mới theo nhóm nội dung</p>

          <div className="flex flex-wrap gap-2 mb-8">
            {categoryOptions.map((category) => {
              const active = selectedCategory === category;
              return (
                <button
                  key={category}
                  type="button"
                  onClick={() => setSelectedCategory(category)}
                  className={[
                    'px-4 py-2 rounded-md border text-sm font-medium transition',
                    active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400',
                  ].join(' ')}
                >
                  {category}
                </button>
              );
            })}
          </div>

          <h3 className="text-2xl font-bold mb-2">Tin mới nhất</h3>
          <p className="text-gray-600 mb-8">Danh sách bài viết cập nhật gần đây</p>

          {newsLoading ? (
            <div className="text-center py-12">Đang tải dữ liệu...</div>
          ) : filteredLatestNews.length > 0 ? (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {filteredLatestNews.map((news) => (
                  <NewsCard key={news.id} {...news} />
                ))}
              </div>
              <PaginationControls
                currentPage={newsState.currentPage}
                lastPage={newsState.lastPage}
                hasMore={newsState.hasMore}
                onPageChange={newsState.goToPage}
              />
            </>
          ) : (
            <div className="text-center py-12 text-gray-500">Không có tin theo chuyên mục đã chọn</div>
          )}
        </div>
      </section>

      <section className="py-14 px-4">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">Dự án tiêu biểu</h2>
          <p className="text-gray-600 mb-8">Các dự án trọng điểm đã triển khai</p>

          {projectsLoading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : projectsList.length > 0 ? (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {projectsList.map((project) => (
                  <ProjectCard key={project.id} {...project} />
                ))}
              </div>
              <PaginationControls
                currentPage={projectsState.currentPage}
                lastPage={projectsState.lastPage}
                hasMore={projectsState.hasMore}
                onPageChange={projectsState.goToPage}
              />
            </>
          ) : (
            <div className="text-center py-12 text-gray-500">Chưa có dự án nào</div>
          )}
        </div>
      </section>

      <section className="py-14 px-4 bg-gray-50 border-y">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">Dịch vụ chính</h2>
          <p className="text-gray-600 mb-8">Các dịch vụ chuyên ngành hiện có</p>

          {servicesLoading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : servicesList.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {servicesList.map((service) => (
                <div key={service.id} className="bg-white p-6 rounded-lg border shadow-sm">
                  <h3 className="text-lg font-semibold mb-2">{service.name}</h3>
                  <p className="text-gray-600 text-sm">Dịch vụ chuyên ngành trong lĩnh vực xây dựng</p>
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-12 text-gray-500">Chưa có dịch vụ nào</div>
          )}
        </div>
      </section>

      <section className="py-14 px-4">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">Liên kết nhanh</h2>
          <p className="text-gray-600 mb-8">Truy cập nhanh các khu vực thông tin chính</p>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {QUICK_LINKS.map((item) => (
              <a
                key={item.title}
                href={item.href}
                className="block border rounded-lg bg-white p-5 hover:border-blue-500 hover:shadow-md transition"
              >
                <h3 className="font-semibold mb-2">{item.title}</h3>
                <p className="text-sm text-gray-600">{item.description}</p>
              </a>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
}


