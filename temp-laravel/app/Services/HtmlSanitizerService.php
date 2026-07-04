<?php

namespace App\Services;

class HtmlSanitizerService
{
    public function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $sanitized = $html;

        $sanitized = (string) preg_replace('#<\s*(script|style|iframe|object|embed|form)[^>]*>.*?<\s*/\s*\1\s*>#is', '', $sanitized);
        $sanitized = (string) preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $sanitized);
        $sanitized = (string) preg_replace('/(href|src)\s*=\s*("|\')\s*javascript:[^"\']*(\2)/i', '$1="#"', $sanitized);
        $sanitized = (string) preg_replace('/(href|src)\s*=\s*("|\')\s*data:text\/html[^"\']*(\2)/i', '$1="#"', $sanitized);
        $sanitized = (string) preg_replace('/style\s*=\s*("|\')[^"\']*expression\s*\([^"\']*("|\')/i', '', $sanitized);

        return trim($sanitized);
    }
}