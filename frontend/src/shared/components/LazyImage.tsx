import React, { useEffect, useRef, useState } from 'react';

interface LazyImageProps {
  src: string;
  alt: string;
  width?: number | string;
  height?: number | string;
  className?: string;
  placeholder?: string;
}

export const LazyImage: React.FC<LazyImageProps> = ({
  src,
  alt,
  width,
  height,
  className = '',
  placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f0f0f0" width="400" height="300"/%3E%3C/svg%3E',
}) => {
  const imgRef = useRef<HTMLImageElement>(null);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    if (!imgRef.current) return;

    // Check if browser supports Intersection Observer
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const img = entry.target as HTMLImageElement;
            img.src = img.dataset.src || src;
            setIsLoaded(true);
            observer.unobserve(img);
          }
        });
      });

      observer.observe(imgRef.current);

      return () => observer.disconnect();
    } else {
      // Fallback for browsers without IntersectionObserver
      imgRef.current.src = src;
      setIsLoaded(true);
    }
  }, [src]);

  return (
    <img
      ref={imgRef}
      data-src={src}
      src={placeholder}
      alt={alt}
      width={width}
      height={height}
      loading="lazy"
      className={`${className} transition-opacity duration-300 ${
        isLoaded ? 'opacity-100' : 'opacity-75'
      }`}
    />
  );
};
