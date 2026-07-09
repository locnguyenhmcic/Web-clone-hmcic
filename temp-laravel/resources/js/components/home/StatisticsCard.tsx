interface StatisticsCardProps {
    label: string;
    value: string;
}

export function StatisticsCard({ label, value }: StatisticsCardProps) {
    return (
        <article className="statistics-card">
            <p>{label}</p>
            <h3>{value}</h3>
        </article>
    );
}
