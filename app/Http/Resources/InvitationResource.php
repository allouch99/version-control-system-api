<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipient_id' => $this->recipient_id,
            'sent_id' => $this->created_at,
            'role' => $this->role,
            'description' => $this->description,
            'group' => Group::find($this->group_id),
        ];

    }
}
