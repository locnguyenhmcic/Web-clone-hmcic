import { siteRoutes } from '@/shared/constants/siteRoutes';

export function SiteHeaderNavigation() {
  return (
    <header className="site-shell-header" aria-label="Header">
      <div className="site-shell-container site-shell-header-inner">
        <a href={siteRoutes.home} className="site-shell-brand">HMCIC Public Portal</a>
        <nav aria-label="Main Navigation">
          <ul className="site-shell-nav-list">
            <li><a href={siteRoutes.home}>Trang chu</a></li>
            <li><a href={siteRoutes.news}>Tin tuc</a></li>
          </ul>
        </nav>
      </div>
    </header>
  );
}

