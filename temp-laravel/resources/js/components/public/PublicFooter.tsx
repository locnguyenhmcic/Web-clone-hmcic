export function PublicFooter() {
    return (
        <footer className="public-footer" aria-label="Public Footer">
            <div className="public-container public-footer-grid">
                <section>
                    <h2>HMCIC</h2>
                    <p>Cong thong tin dien tu phuc vu cong bo thong tin chinh thong trong linh vuc xay dung.</p>
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
                        <li>Email: hmcic.sxd@tphcm.gov.vn</li>
                        <li>Phone: (028) 66.81.51.85</li>
                        <li>Address: 255 Tran Hung Dao, TP.HCM</li>
                    </ul>
                </section>
            </div>

            <div className="public-footer-bottom">
                <div className="public-container">
                    <p>Ban quyen © HMCIC. Cong thong tin dien tu Trung tam Quan ly nha va Giam dinh xay dung.</p>
                </div>
            </div>
        </footer>
    );
}
