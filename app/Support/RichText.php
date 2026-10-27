<?php

namespace App\Support;

final class RichText
{
    public static function sanitize(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $allowedTags = '<p><br><strong><b><em><i><u><s><h2><h3><h4><ul><ol><li><blockquote><pre><code><a>';
        $safeHtml = strip_tags($html, $allowedTags);

        $safeHtml = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $safeHtml) ?? $safeHtml;
        $safeHtml = preg_replace_callback(
            '/\s+(href)\s*=\s*("|\')([^"\']*)(\2)/i',
            static function (array $matches): string {
                $url = trim($matches[3]);

                if (preg_match('/^(?:javascript|data|vbscript):/i', $url)) {
                    return ' href="#"';
                }

                return ' href=' . $matches[2] . e($url) . $matches[4];
            },
            $safeHtml,
        ) ?? $safeHtml;

        return $safeHtml;
    }
}
