import { PublicWebsiteLayout } from '../layouts/PublicWebsiteLayout';

interface FoundationFlags {
    version: string;
    has_header: boolean;
    has_navigation: boolean;
    has_footer: boolean;
    responsive_ready: boolean;
}

interface HomeProps {
    foundation: FoundationFlags;
}

export default function Home({ foundation }: HomeProps) {
    return (
        <PublicWebsiteLayout title="Trang chu">
            <section className="public-hero">
                <p className="public-eyebrow">PHASE 13 · Public Website Foundation</p>
                <h1>Main Layout ready for CMS public website</h1>
                <p>
                    Header, navigation, footer, and responsive behavior are implemented as a reusable
                    website shell. This foundation will host homepage modules in Phase 14.
                </p>
            </section>

            <section className="public-foundation-grid" aria-label="Foundation status">
                <article>
                    <h2>Main Layout</h2>
                    <p>Shared page shell created for all public pages.</p>
                </article>
                <article>
                    <h2>Header</h2>
                    <p>Branded top area with mobile menu toggle.</p>
                </article>
                <article>
                    <h2>Navigation</h2>
                    <p>Main Navigation includes core website routes.</p>
                </article>
                <article>
                    <h2>Footer</h2>
                    <p>Public Footer provides quick links and contact block.</p>
                </article>
            </section>

            <section className="public-status-callout" aria-label="Implementation status">
                <h2>Responsive Layout</h2>
                <p>
                    Mobile, tablet, and desktop breakpoints are handled in the public shell style
                    system.
                </p>
                <code>{foundation.version}</code>
            </section>
        </PublicWebsiteLayout>
    );
}
