<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PublicAnnouncementRepositoryInterface
{
    public function latestPublished(int $limit): Collection;

    public function paginatePublished(int $perPage): LengthAwarePaginator;
}
