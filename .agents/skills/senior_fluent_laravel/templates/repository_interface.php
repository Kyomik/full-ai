<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\{{Model}};

interface {{Model}}RepositoryInterface {
    public function all(): Collection;
    public function find(int $id): ?{{Model}};
    public function create(array $data): {{Model}};
    public function update({{Model}} $model, array $data): bool;
    public function delete({{Model}} $model): bool;
}
