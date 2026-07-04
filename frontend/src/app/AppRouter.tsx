import { lazy, Suspense } from 'react';

const HomePage = lazy(() => import('@/pages/HomePage').then(m => ({ default: m.HomePage })));

export function AppRouter() {
  return (
    <Suspense fallback={<div>Loading...</div>}>
      <HomePage />
    </Suspense>
  );
}


