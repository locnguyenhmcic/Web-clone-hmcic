import axios from 'axios';

const API_BASE_URL = (import.meta.env.VITE_API_URL || '/api').replace(/\/$/, '');

const client = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor for auth token
client.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const backendApiClient = {
  // News endpoints
  getNews: async (page: number = 1, perPage: number = 6, categoryId?: number) => {
    const response = await client.get('/news', {
      params: {
        page,
        per_page: perPage,
        category_id: categoryId,
      },
    });
    return response.data;
  },

  // Projects endpoints
  getProjects: async (page: number = 1, perPage: number = 6, featured: boolean = false) => {
    const response = await client.get('/projects', {
      params: {
        page,
        per_page: perPage,
        featured: featured ? 1 : 0,
      },
    });
    return response.data;
  },

  // Services endpoints
  getServices: async () => {
    const response = await client.get('/services');
    return response.data;
  },

  // FAQ endpoints
  getFAQ: async () => {
    const response = await client.get('/faq');
    return response.data;
  },

  // Media endpoints
  uploadMedia: async (file: File) => {
    const formData = new FormData();
    formData.append('file', file);
    const response = await client.post('/media/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data;
  },

  getMediaStatus: async (id: number) => {
    const response = await client.get(`/media/${id}/status`);
    return response.data;
  },
};

