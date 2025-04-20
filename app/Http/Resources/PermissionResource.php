<?php

namespace App\Http\Resources;

use App\Enums\PermissionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'icon' => $this->icon,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'status' => $this->status,
            'sort' => $this->sort,
            'status_text' => PermissionStatus::Enabled->description()
        ];
    }
}
