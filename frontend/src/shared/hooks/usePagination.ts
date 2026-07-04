import { useState, useCallback } from 'react';

interface PaginationState {
  currentPage: number;
  perPage: number;
  total: number;
  lastPage: number;
  hasMore: boolean;
}

export function usePagination(initialPerPage: number = 12) {
  const [pagination, setPagination] = useState<PaginationState>({
    currentPage: 1,
    perPage: initialPerPage,
    total: 0,
    lastPage: 1,
    hasMore: false,
  });

  const updatePagination = useCallback((data: Partial<PaginationState>) => {
    setPagination((prev) => ({
      ...prev,
      ...data,
    }));
  }, []);

  const nextPage = useCallback(() => {
    if (pagination.hasMore) {
      setPagination((prev) => ({
        ...prev,
        currentPage: prev.currentPage + 1,
      }));
    }
  }, [pagination.hasMore]);

  const prevPage = useCallback(() => {
    if (pagination.currentPage > 1) {
      setPagination((prev) => ({
        ...prev,
        currentPage: prev.currentPage - 1,
      }));
    }
  }, []);

  const goToPage = useCallback((page: number) => {
    if (page >= 1 && page <= pagination.lastPage) {
      setPagination((prev) => ({
        ...prev,
        currentPage: page,
      }));
    }
  }, [pagination.lastPage]);

  return {
    ...pagination,
    updatePagination,
    nextPage,
    prevPage,
    goToPage,
  };
}
