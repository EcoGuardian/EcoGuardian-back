<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotResource extends JsonResource
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
            "name" => $this->name,
            "city" => $this->city,
            "location" => $this->location,
            "type" => new TypeResource($this->type),
            "created_at" => Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
