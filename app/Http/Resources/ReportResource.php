<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "photo_path" => $this->photo_path,
            "description" => $this->description,
            "location" => $this->location,
            "status" => $this->status,
            "user" => new UserResource(User::find($this->user_id)),
            "created_at" => Carbon::parse($this->created_at)->toDateTimeString(),
            "updated_at" => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
}
