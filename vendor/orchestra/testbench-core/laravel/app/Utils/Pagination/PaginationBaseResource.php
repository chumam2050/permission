<?php

declare(strict_types=1);

namespace App\Utils\Pagination;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationBaseResource extends JsonResource
{
    /** @var string */
    public static $wrap = 'items';

    /**
     * Customize the pagination information for the resource.
     */
    public static function collection(mixed $resource): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return tap(new PaginationBaseCollection($resource, static::class), function ($collection): void {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
