interface LoadingSkeletonProps {
    variant?: 'hero' | 'card' | 'list';
    count?: number;
}

export function LoadingSkeleton({ variant = 'card', count = 1 }: LoadingSkeletonProps) {
    return (
        <div className={`skeleton-wrap skeleton-${variant}`}>
            {Array.from({ length: count }).map((_, index) => (
                <div key={`${variant}-${index}`} className="skeleton-item" aria-hidden="true" />
            ))}
        </div>
    );
}
