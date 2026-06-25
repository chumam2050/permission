<?php

namespace App\Http\Resources;

use App\Utils\Pagination\PaginationBaseResource;
use Illuminate\Http\Request;

class GroupPermissionResource extends PaginationBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->permission_id,
            'group_id' => $this->group_id,
            'permission_id' => $this->permission_id,
            'permission' => $this->whenLoaded('permission', fn () => new PermissionResource($this->permission)),
        ];
    }
}
