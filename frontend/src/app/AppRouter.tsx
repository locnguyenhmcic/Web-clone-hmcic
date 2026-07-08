import { lazy, Suspense } from 'react';
import { MainLayout } from '@/app/MainLayout';

const HomePage = lazy(() => import('@/pages/HomePage').then(m => ({ default: m.HomePage })));

export function AppRouter() {
  return (
    <MainLayout>
      <Suspense fallback={<div>Loading...</div>}>
        <HomePage />
      </Suspense>
    </MainLayout>
  );
}


