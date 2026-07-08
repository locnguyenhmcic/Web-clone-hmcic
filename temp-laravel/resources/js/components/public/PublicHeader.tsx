interface PublicHeaderProps {
    onToggleMenu: () => void;
    isMenuOpen: boolean;
}

export function PublicHeader({ onToggleMenu, isMenuOpen }: PublicHeaderProps) {
    return (
        <header className="public-header">
            <div className="public-container public-header-inner">
                <a href="/" className="public-logo" aria-label="HMCIC home page">
                    <span className="public-logo-mark" aria-hidden="true">H</span>
                    <span className="public-logo-text">HMCIC Public Portal</span>
                </a>

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
        </header>
    );
}
