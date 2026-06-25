<?php

namespace App\Http\Resources;

use App\Utils\Pagination\PaginationBaseResource;
use Illuminate\Http\Request;

class PermissionResource extends PaginationBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module' => $this->module,
            'feature' => $this->feature,
            'action' => $this->action,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
