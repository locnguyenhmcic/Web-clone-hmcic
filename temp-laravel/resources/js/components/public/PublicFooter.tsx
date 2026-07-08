export function PublicFooter() {
    return (
        <footer className="public-footer" aria-label="Public Footer">
            <div className="public-container public-footer-grid">
                <section>
                    <h2>HMCIC</h2>
                    <p>Public website foundation for CMS-driven content publishing.</p>
                </section>

                <section>
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/">Trang chu</a></li>
                        <li><a href="/tin-tuc">Tin tuc</a></li>
                        <li><a href="/du-an">Du an</a></li>
                        <li><a href="/lien-he">Lien he</a></li>
                    </ul>
                </section>

                <section>
                    <h3>Contact</h3>
                    <ul>
                        <li>Email: info@hmcic.local</li>
                        <li>Phone: (028) 0000 0000</li>
                        <li>Address: Ho Chi Minh City</li>
                    </ul>
                </section>
            </div>

            <div className="public-footer-bottom">
                <div className="public-container">
                    <p>Public Footer · HMCIC CMS</p>
                </div>
            </div>
        </footer>
    );
}
