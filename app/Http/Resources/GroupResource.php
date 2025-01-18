<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $users = UserResource::collection($this->memberships)  ;
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'bg_image_url' => $this->bg_image_url,
            'icon_image_url' => $this->icon_image_url,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => FileResource::collection($this->files()->get()),
            'owner' => new UserResource($this->user),
            'users' => UserResource::collection($this->memberships),
        ];
    }
}
