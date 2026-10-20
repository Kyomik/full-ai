<?php

namespace App\Repositories\Eloquent;

use App\Models\Announcement;
use App\Repositories\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{
    /**
     * Get the latest published announcements.
     */
    public function latestPublished(int $limit): Collection
    {
        return Announcement::where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_on')
                    ->orWhereDate('published_on', '<=', today());
            })
            ->latest('published_on')
            ->take($limit)
            ->get();
    }
}

?>