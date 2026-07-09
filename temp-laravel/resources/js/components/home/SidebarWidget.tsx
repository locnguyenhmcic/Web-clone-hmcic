interface SidebarItem {
    title: string;
    href: string;
    date?: string;
}

interface SidebarWidgetProps {
    title: string;
    items: SidebarItem[];
}

export function SidebarWidget({ title, items }: SidebarWidgetProps) {
    return (
        <aside className="sidebar-widget" aria-label={title}>
            <h3>{title}</h3>
            <ul>
                {items.map((item) => (
                    <li key={`${item.href}-${item.title}`}>
                        <a href={item.href}>{item.title}</a>
                        {item.date ? <small>{item.date}</small> : null}
                    </li>
                ))}
            </ul>
        </aside>
    );
}
