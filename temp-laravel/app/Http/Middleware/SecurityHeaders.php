<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip security headers in development
        if (app()->environment('local', 'development')) {
            return $response;
        }

        $isAdminSurface = $request->is('admin')
            || $request->is('admin/*')
            || $request->is('livewire/*');

        $scriptSrc = $isAdminSurface
            ? "'self' 'unsafe-inline' 'unsafe-eval'"
            : "'self' 'unsafe-inline'";

        $styleSrc = $isAdminSurface
            ? "'self' 'unsafe-inline' https://fonts.bunny.net"
            : "'self' 'unsafe-inline'";

        $fontSrc = $isAdminSurface
            ? "'self' data: https://fonts.bunny.net"
            : "'self' data:";

        $imgSrc = $isAdminSurface
            ? "'self' data: blob: https://ui-avatars.com"
            : "'self' data: blob:";

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'; img-src {$imgSrc}; media-src 'self' blob:; style-src {$styleSrc}; font-src {$fontSrc}; script-src {$scriptSrc}"
        );

        return $response;
    }
}