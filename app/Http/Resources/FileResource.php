<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($user = User::find($this->locked_by))
            $user = new UserResource($user);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->temporaryUrl,
            'version' => $this->version,
            'locked_by' => $user,
        ];
    }

}
