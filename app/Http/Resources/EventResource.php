<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\isTrue;

class EventResource extends JsonResource
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
            "title" => $this->title,
            "location" => [
                "lat" => $this->latitude,
                "long" => $this->longitude
            ],
            "datetime" => $this->datetime,
            "likes" => $this->likeCount(),
            "isLiked" => $this->userEvents()->where('user_id', auth()->user()->id)->first() === null ? false : true,
            "description" => $this->description,
            "created_at" => explode(' ', Carbon::parse($this->created_at)->toDateTimeString())
        ];
    }
}
