import { useEffect, useState } from 'react';

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

export default function Home() {
  const [projectsList, setProjectsList] = useState<Project[]>([]);
  const [newsList, setNewsList] = useState<News[]>([]);
  const [servicesList, setServicesList] = useState<Service[]>([]);
  const [loading, setLoading] = useState(true);
  const [projectsPage, setProjectsPage] = useState(1);
  const [newsPage, setNewsPage] = useState(1);
  const [projectsLastPage, setProjectsLastPage] = useState(1);
  const [newsLastPage, setNewsLastPage] = useState(1);

  const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

  // Fetch data on mount and page changes
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        
        // Fetch projects
        const projectsRes = await fetch(`${apiUrl}/projects?page=${projectsPage}&per_page=6&featured=1`);
        const projectsData = await projectsRes.json();
        setProjectsList(projectsData.data || []);
        setProjectsLastPage(projectsData.pagination?.last_page || 1);

        // Fetch news
        const newsRes = await fetch(`${apiUrl}/news?page=${newsPage}&per_page=6`);
        const newsData = await newsRes.json();
        setNewsList(newsData.data || []);
        setNewsLastPage(newsData.pagination?.last_page || 1);

        // Fetch services
        const servicesRes = await fetch(`${apiUrl}/services`);
        const servicesData = await servicesRes.json();
        setServicesList(servicesData.data || []);
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [projectsPage, newsPage]);

  const formatDate = (dateStr: string) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN');
  };

  return (
    <main className="min-h-screen bg-white">
      {/* Hero Section */}
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

          {loading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : projectsList.length > 0 ? (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {projectsList.map((project) => (
                  <div key={project.id} className="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    {project.thumbnail_url ? (
                      <img
                        src={project.thumbnail_url}
                        alt={project.title}
                        className="w-full h-48 object-cover"
                        loading="lazy"
                      />
                    ) : (
                      <div className="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span className="text-gray-400">No image</span>
                      </div>
                    )}
                    <div className="p-4">
                      <h3 className="font-semibold text-lg line-clamp-2 mb-2">{project.title}</h3>
                      <p className="text-gray-600 text-sm line-clamp-2 mb-4">{project.excerpt}</p>
                      <div className="flex justify-between items-center text-xs text-gray-500">
                        <span>{formatDate(project.created_at)}</span>
                        {project.featured && <span className="bg-blue-100 text-blue-700 px-2 py-1 rounded">Tiêu biểu</span>}
                      </div>
                    </div>
                  </div>
                ))}
              </div>
              
              {/* Pagination for Projects */}
              <div className="flex justify-center gap-2">
                <button
                  onClick={() => setProjectsPage(Math.max(1, projectsPage - 1))}
                  disabled={projectsPage === 1}
                  className="px-4 py-2 border rounded disabled:opacity-50"
                >
                  ← Trước
                </button>
                {[...Array(projectsLastPage)].map((_, i) => (
                  <button
                    key={i + 1}
                    onClick={() => setProjectsPage(i + 1)}
                    className={`px-4 py-2 border rounded ${projectsPage === i + 1 ? 'bg-blue-600 text-white' : ''}`}
                  >
                    {i + 1}
                  </button>
                ))}
                <button
                  onClick={() => setProjectsPage(Math.min(projectsLastPage, projectsPage + 1))}
                  disabled={projectsPage === projectsLastPage}
                  className="px-4 py-2 border rounded disabled:opacity-50"
                >
                  Sau →
                </button>
              </div>
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

          {loading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : servicesList.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {servicesList.map((service) => (
                <div key={service.id} className="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
                  <h3 className="text-lg font-semibold mb-2">{service.name}</h3>
                  <p className="text-gray-600 text-sm">Dịch vụ chuyên ngành trong lĩnh vực xây dựng và quản lý nhà</p>
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

          {loading ? (
            <div className="text-center py-12">Đang tải...</div>
          ) : newsList.length > 0 ? (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {newsList.map((news) => (
                  <div key={news.id} className="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                    {news.thumbnail_url ? (
                      <img
                        src={news.thumbnail_url}
                        alt={news.title}
                        className="w-full h-48 object-cover"
                        loading="lazy"
                      />
                    ) : (
                      <div className="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span className="text-gray-400">No image</span>
                      </div>
                    )}
                    <div className="p-4">
                      <h3 className="font-semibold text-lg line-clamp-2 mb-2">{news.title}</h3>
                      <p className="text-gray-600 text-sm line-clamp-2 mb-4">{news.excerpt}</p>
                      <div className="flex justify-between items-center text-xs text-gray-500">
                        <span>{formatDate(news.created_at)}</span>
                        {news.category && <span className="bg-gray-100 text-gray-700 px-2 py-1 rounded">{news.category}</span>}
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {/* Pagination for News */}
              <div className="flex justify-center gap-2">
                <button
                  onClick={() => setNewsPage(Math.max(1, newsPage - 1))}
                  disabled={newsPage === 1}
                  className="px-4 py-2 border rounded disabled:opacity-50"
                >
                  ← Trước
                </button>
                {[...Array(newsLastPage)].map((_, i) => (
                  <button
                    key={i + 1}
                    onClick={() => setNewsPage(i + 1)}
                    className={`px-4 py-2 border rounded ${newsPage === i + 1 ? 'bg-blue-600 text-white' : ''}`}
                  >
                    {i + 1}
                  </button>
                ))}
                <button
                  onClick={() => setNewsPage(Math.min(newsLastPage, newsPage + 1))}
                  disabled={newsPage === newsLastPage}
                  className="px-4 py-2 border rounded disabled:opacity-50"
                >
                  Sau →
                </button>
              </div>
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
            <input type="hidden" name="_token" value="" />
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
