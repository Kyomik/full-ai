<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DemoMedia
{
    public static function placeholder(): string
    {
        $source = public_path('images/palopo-seal.jpeg');
        $relativePath = 'demo/fiktif/palopo-placeholder.jpeg';
        $destination = storage_path('app/public/' . $relativePath);

        if (! File::exists($source)) {
            throw new \RuntimeException('Gambar contoh public/images/palopo-seal.jpeg tidak ditemukan.');
        }

        File::ensureDirectoryExists(dirname($destination));

        if (! File::exists($destination)) {
            File::copy($source, $destination);
        }

        return $relativePath;
    }

    public static function galleryPreview(int $variant): string
    {
        $palette = [
            ['#f7bd83', '#e77b61', '#315e54'],
            ['#9fc9bd', '#497f70', '#f2d88c'],
            ['#b7a6d9', '#6f5b9e', '#e9a17d'],
            ['#efc7a6', '#bd6e52', '#5c6d83'],
            ['#a9c6e6', '#4b718e', '#f0d19b'],
            ['#c7d59a', '#718253', '#d27d65'],
        ];
        [$sky, $accent, $highlight] = $palette[abs($variant) % count($palette)];
        $relativePath = 'demo/fiktif/gallery-preview-' . (abs($variant) % count($palette) + 1) . '.svg';
        $gradientId = 'scene-' . (abs($variant) % count($palette) + 1);
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 960 600" role="img" aria-label="Ilustrasi simulasi suasana festival budaya">
  <defs>
    <linearGradient id="{$gradientId}" x1="0" y1="0" x2="1" y2="1"><stop stop-color="{$sky}"/><stop offset="1" stop-color="#f7efe1"/></linearGradient>
    <linearGradient id="ground-{$gradientId}" x1="0" y1="0" x2="1" y2="1"><stop stop-color="{$accent}"/><stop offset="1" stop-color="{$highlight}"/></linearGradient>
  </defs>
  <rect width="960" height="600" fill="url(#{$gradientId})"/>
  <circle cx="735" cy="152" r="76" fill="{$highlight}" opacity=".88"/>
  <path d="M0 403 206 235l190 139 185-184 379 235v175H0Z" fill="url(#ground-{$gradientId})" opacity=".9"/>
  <path d="M0 477 244 342l173 113 234-139 309 139v145H0Z" fill="{$accent}" opacity=".76"/>
  <path d="M0 510q180-75 350 0t350 0 260-8v98H0Z" fill="#263c35" opacity=".82"/>
  <g fill="none" stroke="#fff" stroke-width="8" opacity=".78">
    <path d="M90 100h142m-71-71v142M840 334h62m-31-31v62"/>
    <circle cx="296" cy="136" r="28"/><circle cx="642" cy="418" r="22"/>
  </g>
  <text x="56" y="548" fill="#fff" font-family="Arial,sans-serif" font-size="20" font-weight="700" letter-spacing="4">MOMEN BUDAYA · SIMULASI</text>
</svg>
SVG;

        Storage::disk('public')->put($relativePath, $svg);

        return $relativePath;
    }
}
