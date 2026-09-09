<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface CatalogItemRepositoryInterface
{
    public function getPublishedWithFilters(array $filters): LengthAwarePaginator;
    public function findBySlug(string $tefaUnitId, string $slug): ?\App\Models\CatalogItem;
}
