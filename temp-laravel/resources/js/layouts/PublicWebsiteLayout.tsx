import { Head, usePage } from '@inertiajs/react';
import { type ReactNode, useMemo, useState } from 'react';
import { PublicFooter } from '../components/public/PublicFooter';
import { PublicHeader } from '../components/public/PublicHeader';
import { PublicNavigation } from '../components/public/PublicNavigation';

interface PublicWebsiteLayoutProps {
    title: string;
    children: ReactNode;
}

export function PublicWebsiteLayout({ title, children }: PublicWebsiteLayoutProps) {
    const page = usePage();
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const navItems = useMemo(
        () => [
            { href: '/', label: 'Trang chu' },
            { href: '/gioi-thieu', label: 'Gioi thieu' },
            { href: '/tin-tuc', label: 'Tin tuc' },
            { href: '/du-an', label: 'Du an' },
            { href: '/hoi-dap', label: 'Hoi dap' },
            { href: '/lien-he', label: 'Lien he' },
        ],
        [],
    );

    const currentPath = (page.url.split('?')[0] || '/') as string;

    return (
        <>
            <Head title={title} />

            <div className="public-shell">
                <PublicHeader
                    isMenuOpen={isMenuOpen}
                    onToggleMenu={() => setIsMenuOpen((open) => !open)}
                />

                <PublicNavigation
                    currentPath={currentPath}
                    isMenuOpen={isMenuOpen}
                    items={navItems}
                />

                <main className="public-main">
                    <div className="public-container">{children}</div>
                </main>

                <PublicFooter />
            </div>
        </>
    );
}
