import type {
    CollectionResponse,
    FaqItem,
    NewsItem,
    ProjectItem,
    ServiceItem,
    SimpleCollectionResponse,
} from '../types/homepage';

const API_BASE_URL = (import.meta.env.VITE_API_URL || '/api').replace(/\/$/, '');

async function fetchJson<T>(path: string): Promise<T> {
    const response = await fetch(`${API_BASE_URL}${path}`);

    if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
    }

    return response.json() as Promise<T>;
}

export const publicApi = {
    getNews: (page = 1, perPage = 8) =>
        fetchJson<CollectionResponse<NewsItem>>(`/news?page=${page}&per_page=${perPage}`),

    getProjects: (page = 1, perPage = 8, featured = true) =>
        fetchJson<CollectionResponse<ProjectItem>>(
            `/projects?page=${page}&per_page=${perPage}&featured=${featured ? 1 : 0}`,
        ),

    getServices: () => fetchJson<SimpleCollectionResponse<ServiceItem>>('/services'),

    getFaq: () => fetchJson<SimpleCollectionResponse<FaqItem>>('/faq'),
};
