export interface FoundationFlags {
    version: string;
    has_header: boolean;
    has_navigation: boolean;
    has_footer: boolean;
    responsive_ready: boolean;
}

export interface NewsItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    thumbnail_url: string | null;
    category: string | null;
    created_at: string;
}

export interface ProjectItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    thumbnail_url: string | null;
    created_at: string;
    featured: boolean;
}

export interface ServiceItem {
    id: number;
    name: string;
    slug: string;
}

export interface FaqItem {
    id?: number;
    question?: string;
    answer?: string;
    title?: string;
    slug?: string;
}

export interface PaginationMeta {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    has_more: boolean;
}

export interface CollectionResponse<T> {
    data: T[];
    pagination: PaginationMeta;
}

export interface SimpleCollectionResponse<T> {
    data: T[];
}

export interface AsyncState<T> {
    loading: boolean;
    data: T;
    error: string | null;
}
