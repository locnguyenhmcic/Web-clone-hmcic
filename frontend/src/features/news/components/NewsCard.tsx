import { LazyImage } from '@/shared/components/LazyImage';
import { Link } from '@inertiajs/react';

interface NewsCardProps {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  thumbnail_url: string | null;
  category: string | null;
  created_at: string;
}

export function NewsCard({
  id,
  title,
  slug,
  excerpt,
  thumbnail_url,
  category,
  created_at,
}: NewsCardProps) {
  return (
    <article className="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
      {thumbnail_url && (
        <div className="w-full h-48 bg-gray-200 overflow-hidden">
          <LazyImage
            src={thumbnail_url}
            alt={title}
            className="w-full h-full object-cover"
            width={400}
            height={300}
          />
        </div>
      )}
      <div className="p-4">
        <h3 className="text-lg font-semibold mb-2 line-clamp-2">{title}</h3>
        {excerpt && <p className="text-gray-600 text-sm mb-3 line-clamp-2">{excerpt}</p>}
        <div className="flex items-center justify-between gap-2 text-xs">
          <span className="text-gray-500">{created_at}</span>
          {category && <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded">{category}</span>}
        </div>
      </div>
    </article>
  );
}


