<?php

namespace App\Http\Resources;

use App\Utils\Pagination\PaginationBaseResource;
use Illuminate\Http\Request;

class GroupMemberResource extends PaginationBaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
            'role' => $this->role,
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'group' => $this->whenLoaded('group', function () {
                return new GroupResource($this->group);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
