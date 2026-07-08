interface NavItem {
    href: string;
    label: string;
}

interface PublicNavigationProps {
    items: NavItem[];
    currentPath: string;
    isMenuOpen: boolean;
}

export function PublicNavigation({ items, currentPath, isMenuOpen }: PublicNavigationProps) {
    return (
        <nav
            id="public-navigation"
            className={`public-navigation${isMenuOpen ? ' is-open' : ''}`}
            aria-label="Main Navigation"
        >
            <div className="public-container">
                <ul>
                    {items.map((item) => {
                        const isActive = currentPath === item.href;

                        return (
                            <li key={item.href}>
                                <a href={item.href} className={isActive ? 'is-active' : ''}>
                                    {item.label}
                                </a>
                            </li>
                        );
                    })}
                </ul>
            </div>
        </nav>
    );
}
