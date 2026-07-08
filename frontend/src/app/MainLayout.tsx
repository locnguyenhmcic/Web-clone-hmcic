import type { ReactNode } from 'react';
import { SiteFooterLinks } from '@/shared/components/SiteFooterLinks';
import { SiteHeaderNavigation } from '@/shared/components/SiteHeaderNavigation';

export function MainLayout({ children }: { children: ReactNode }) {
  return (
    <div className="site-shell-root">
      <style>
        {`
          .site-shell-root {
            min-height: 100vh;
            background: linear-gradient(180deg, #f7fbff 0%, #ecf4ff 100%);
            color: #122a45;
            display: flex;
            flex-direction: column;
            font-family: "Instrument Sans", "Be Vietnam Pro", "Segoe UI", sans-serif;
          }

          .site-shell-container {
            width: min(1100px, calc(100% - 2rem));
            margin: 0 auto;
          }

          .site-shell-header {
            border-bottom: 1px solid rgba(18, 42, 69, 0.14);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(6px);
          }

          .site-shell-header-inner {
            min-height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 0.4rem 0;
          }

          .site-shell-brand {
            text-decoration: none;
            color: #0f3f73;
            font-weight: 700;
            letter-spacing: 0.01em;
          }

          .site-shell-nav-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 0.4rem;
          }

          .site-shell-nav-list a {
            display: inline-flex;
            align-items: center;
            min-height: 2.2rem;
            padding: 0 0.8rem;
            text-decoration: none;
            color: #eaf2fd;
            border-radius: 0.45rem;
            background: #1a4f8f;
            font-weight: 600;
          }

          .site-shell-main {
            flex: 1;
            padding: 1.2rem 0 2rem;
          }

          .site-shell-footer {
            background: #0a2038;
            color: #d4e2f4;
            border-top: 1px solid rgba(212, 226, 244, 0.2);
          }

          .site-shell-footer p {
            margin: 0;
            padding: 0.85rem 0;
            font-size: 0.9rem;
          }

          @media (max-width: 768px) {
            .site-shell-nav-list {
              width: 100%;
            }
            .site-shell-nav-list li {
              flex: 1;
            }
            .site-shell-nav-list a {
              width: 100%;
              justify-content: center;
            }
          }
        `}
      </style>

      <SiteHeaderNavigation />
      <main className="site-shell-main">
        <div className="site-shell-container">{children}</div>
      </main>
      <SiteFooterLinks />
    </div>
  );
}

