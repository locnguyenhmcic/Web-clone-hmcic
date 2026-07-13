import type {
    CollectionResponse,
    FaqItem,
    NewsCategory,
    NewsDetailItem,
    NewsItem,
    ProjectItem,
    ServiceItem,
    SimpleCollectionResponse,
} from '../types/homepage';

const configuredApiUrl = import.meta.env.VITE_API_URL?.trim();
const isLocalApiUrl =
    configuredApiUrl !== undefined
    && /^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?(\/|$)/i.test(configuredApiUrl);

const API_BASE_URL = (configuredApiUrl && !isLocalApiUrl ? configuredApiUrl : '/api').replace(/\/$/, '');

async function fetchJson<T>(path: string): Promise<T> {
    const response = await fetch(`${API_BASE_URL}${path}`);

    if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
    }

    return response.json() as Promise<T>;
}

export const publicApi = {
    getNews: (
        page = 1,
        perPage = 8,
        options: { categoryId?: number; search?: string } = {},
    ) => {
        const params = new URLSearchParams({
            page: String(page),
            per_page: String(perPage),
        });

        if (options.categoryId) {
            params.set('category_id', String(options.categoryId));
        }

        if (options.search && options.search.trim() !== '') {
            params.set('search', options.search.trim());
        }

        return fetchJson<CollectionResponse<NewsItem>>(`/news?${params.toString()}`);
    },

    getNewsCategories: () =>
        fetchJson<SimpleCollectionResponse<NewsCategory>>('/news/categories'),

    getNewsDetail: (slug: string) =>
        fetchJson<{ data: NewsDetailItem }>(`/news/${encodeURIComponent(slug)}`),

    getProjects: (page = 1, perPage = 8, featured = true) =>
        fetchJson<CollectionResponse<ProjectItem>>(
            `/projects?page=${page}&per_page=${perPage}&featured=${featured ? 1 : 0}`,
        ),

    getServices: () => fetchJson<SimpleCollectionResponse<ServiceItem>>('/services'),

    getFaq: () => fetchJson<SimpleCollectionResponse<FaqItem>>('/faq'),
};
