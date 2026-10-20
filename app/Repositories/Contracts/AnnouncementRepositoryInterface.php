<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AnnouncementRepositoryInterface
{
    /**
     * Get the latest published announcements.
     */
    public function latestPublished(int $limit): Collection;
}

?>