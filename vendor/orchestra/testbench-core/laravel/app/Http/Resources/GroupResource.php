<?php

namespace App\Http\Resources;

use App\Utils\Pagination\PaginationBaseResource;
use Illuminate\Http\Request;

class GroupResource extends PaginationBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
