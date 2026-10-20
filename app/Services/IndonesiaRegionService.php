<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IndonesiaRegionService
{
    public function get(string $level, ?string $parent = null): array
    {
        abort_unless(in_array($level, ['provinces','regencies','districts','villages'], true), 404);
        if ($level !== 'provinces' && !$parent) return [];
        $baseUrl = rtrim((string) config('services.indonesia_regions.base_url'), '/');
        $url = $baseUrl.'/'.($level === 'provinces' ? 'provinces.json' : $level.'/'.$parent.'.json');
        $cached = Cache::get('regions:'.md5($url));
        if ($cached !== null) return $cached;
        try {
            $items = Http::connectTimeout(config('services.indonesia_regions.connect_timeout', 4))
                ->timeout(config('services.indonesia_regions.timeout', 7))
                ->retry(config('services.indonesia_regions.retry_times', 1), config('services.indonesia_regions.retry_delay', 200))
                ->get($url);
            if (!$items->successful()) return [];
            $regions = collect($items->json())->map(fn ($item) => ['id'=>$item['id'], 'name'=>Str::title(Str::lower($item['name']))])->all();
        } catch (\Throwable $exception) {
            report($exception);
            return [];
        }
        Cache::put('regions:'.md5($url), $regions, now()->addDays(config('services.indonesia_regions.cache_days', 7)));
        return $regions;
    }
}
