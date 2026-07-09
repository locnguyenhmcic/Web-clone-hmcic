import type { ReactNode } from 'react';

interface SectionFrameProps {
    title: string;
    subtitle?: string;
    actionLabel?: string;
    actionHref?: string;
    children: ReactNode;
}

export function SectionFrame({ title, subtitle, actionLabel, actionHref, children }: SectionFrameProps) {
    return (
        <section className="home-section-frame" aria-label={title}>
            <header className="home-section-frame-header">
                <div>
                    <h2>{title}</h2>
                    {subtitle ? <p>{subtitle}</p> : null}
                </div>
                {actionLabel && actionHref ? (
                    <a className="home-section-frame-action" href={actionHref}>
                        {actionLabel}
                    </a>
                ) : null}
            </header>
            {children}
        </section>
    );
}
