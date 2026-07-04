'use client';

import { useEffect, useState } from 'react';
import { backendApiClient } from '@/shared/api/backendApiClient';
import { usePagination } from '@/shared/hooks/usePagination';
import { PaginationControls } from '@/shared/components/PaginationControls';
import { ProjectCard } from '@/features/project/components/ProjectCard';
import { NewsCard } from '@/features/news/components/NewsCard';

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

export function HomePage() {
  // News state
  const newsState = usePagination(6);
  const [newsList, setNewsList] = useState<News[]>([]);
  const [newsLoading, setNewsLoading] = useState(false);

  // Projects state
  const projectsState = usePagination(6);
  const [projectsList, setProjectsList] = useState<Project[]>([]);
  const [projectsLoading, setProjectsLoading] = useState(false);

  // Services state
  const [servicesList, setServicesList] = useState<Service[]>([]);
  const [servicesLoading, setServicesLoading] = useState(false);

  // Fetch news
  useEffect(() => {
    const fetchNews = async () => {
      setNewsLoading(true);
      try {
        const data = await backendApiClient.getNews(newsState.currentPage, 6);
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

  // Fetch projects
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

  // Fetch services (only once)
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

  return (
    <main className="min-h-screen bg-white">
      {/* Hero / Intro Section */}
      <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16 px-4">
        <div className="max-w-5xl mx-auto text-center">
          <h1 className="text-4xl md:text-5xl font-bold mb-4">
            Trung tâm Quản lý Nhà và Giám định Xây dựng
          </h1>
          <p className="text-lg mb-8 opacity-90">
            Đơn vị sự nghiệp công lập trực thuộc Sở Xây dựng TP.HCM, cung cấp các dịch vụ
            quản lý nhà, giám định xây dựng và tư vấn chuyên ngành.
          </p>
          <a
            href="/gioi-thieu"
            className="inline-block bg-white text-blue-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition"
          >
            Xem thêm
          </a>
        </div>
      </section>

      {/* Featured Projects Section */}
      <section className="py-16 px-4">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">🚀 DỰ ÁN TIÊU BIỂU</h2>
          <p className="text-gray-600 mb-8">Các dự án nổi bật đã hoàn thành</p>

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

      {/* Services Section */}
      <section className="py-16 px-4 bg-gray-50">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">📋 DỊCH VỤ CHÍNH</h2>
          <p className="text-gray-600 mb-8">Các dịch vụ chuyên ngành của chúng tôi</p>

          {servicesLoading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : servicesList.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {servicesList.map((service) => (
                <div key={service.id} className="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
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

      {/* Recent News Section */}
      <section className="py-16 px-4">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-bold mb-2">📰 TIN TỨC MỚI NHẤT</h2>
          <p className="text-gray-600 mb-8">Cập nhật thông tin mới nhất từ trung tâm</p>

          {newsLoading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : newsList.length > 0 ? (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {newsList.map((news) => (
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
            <div className="text-center py-12 text-gray-500">Chưa có tin tức nào</div>
          )}
        </div>
      </section>

      {/* Contact Form Section */}
      <section className="py-16 px-4 bg-blue-50">
        <div className="max-w-2xl mx-auto">
          <h2 className="text-3xl font-bold mb-2 text-center">✉️ LIÊN HỆ CHÚNG TÔI</h2>
          <p className="text-gray-600 mb-8 text-center">Gửi thông tin liên hệ để chúng tôi có thể hỗ trợ bạn</p>

          <form className="bg-white p-8 rounded-lg shadow-md" method="POST" action="/contact">
            <div className="mb-6">
              <label className="block text-sm font-medium mb-2">Họ và tên</label>
              <input type="text" name="name" required className="w-full px-4 py-2 border rounded-lg" />
            </div>

            <div className="mb-6">
              <label className="block text-sm font-medium mb-2">Email</label>
              <input type="email" name="email" required className="w-full px-4 py-2 border rounded-lg" />
            </div>

            <div className="mb-6">
              <label className="block text-sm font-medium mb-2">Điện thoại</label>
              <input type="tel" name="phone" className="w-full px-4 py-2 border rounded-lg" />
            </div>

            <div className="mb-6">
              <label className="block text-sm font-medium mb-2">Chủ đề</label>
              <input type="text" name="subject" className="w-full px-4 py-2 border rounded-lg" />
            </div>

            <div className="mb-6">
              <label className="block text-sm font-medium mb-2">Tin nhắn</label>
              <textarea name="message" rows={5} required className="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>

            <button
              type="submit"
              className="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition"
            >
              Gửi
            </button>
          </form>
        </div>
      </section>
    </main>
  );
}


