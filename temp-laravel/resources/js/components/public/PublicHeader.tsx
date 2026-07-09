interface PublicHeaderProps {
    onToggleMenu: () => void;
    isMenuOpen: boolean;
}

export function PublicHeader({ onToggleMenu, isMenuOpen }: PublicHeaderProps) {
    return (
        <header className="public-header">
            <div className="public-topbar">
                <div className="public-container public-topbar-inner">
                    <p>TRUC THUOC SO XAY DUNG TP.HCM</p>
                    <ul>
                        <li>(028) 66.81.51.85</li>
                        <li>hmcic.sxd@tphcm.gov.vn</li>
                    </ul>
                </div>
            </div>

            <div className="public-container public-header-inner">
                <a href="/" className="public-logo" aria-label="HMCIC home page">
                    <span className="public-logo-mark" aria-hidden="true">H</span>
                    <span className="public-logo-copy">
                        <strong>HMCIC Public Portal</strong>
                        <small>Trung tam Quan ly nha va Giam dinh xay dung</small>
                    </span>
                </a>

                <div className="public-header-right">
                    <a href="/lien-he" className="public-cta-link">Lien he ngay</a>
                    <button
                        type="button"
                        className="public-menu-toggle"
                        onClick={onToggleMenu}
                        aria-expanded={isMenuOpen}
                        aria-controls="public-navigation"
                        aria-label="Toggle main navigation"
                    >
                        <span />
                        <span />
                        <span />
                    </button>
                </div>
            </div>
        </header>
    );
}
