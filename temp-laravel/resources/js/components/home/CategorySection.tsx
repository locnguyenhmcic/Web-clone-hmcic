interface CategoryItem {
    name: string;
    count: number;
}

interface CategorySectionProps {
    items: CategoryItem[];
}

export function CategorySection({ items }: CategorySectionProps) {
    return (
        <div className="category-chip-grid">
            {items.map((item) => (
                <article key={item.name} className="category-chip-card">
                    <h3>{item.name}</h3>
                    <p>{item.count} bai viet</p>
                </article>
            ))}
        </div>
    );
}
