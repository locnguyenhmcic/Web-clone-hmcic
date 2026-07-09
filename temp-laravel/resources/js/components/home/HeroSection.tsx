interface HeroSectionProps {
    title: string;
    description: string;
}

export function HeroSection({ title, description }: HeroSectionProps) {
    return (
        <section className="home-hero" aria-label="Hero Section">
            <div className="home-hero-content">
                <p className="home-hero-kicker">TRUNG TAM QUAN LY NHA VA GIAM DINH XAY DUNG</p>
                <h1>{title}</h1>
                <p>{description}</p>
                <div className="home-hero-actions">
                    <a className="home-button home-button-primary" href="/tin-tuc">
                        Xem tin tuc
                    </a>
                    <a className="home-button home-button-ghost" href="/lien-he">
                        Lien he
                    </a>
                </div>
            </div>
            <div className="home-hero-search" role="search">
                <label htmlFor="home-search-input">Tim kiem nhanh</label>
                <div className="home-search-row">
                    <input id="home-search-input" type="search" placeholder="Nhap tu khoa bai viet, thong bao..." />
                    <button type="button">Tim</button>
                </div>
            </div>
        </section>
    );
}
