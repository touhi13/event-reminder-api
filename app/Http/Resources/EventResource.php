<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'title'               => $this->title,
            'description'         => $this->description,
            'event_date'          => $this->event_date,
            'reminder_recipients' => $this->reminder_recipients,
            'event_id'            => $this->event_id,
            'updated_at'          => $this->updated_at,
            'created_at'          => $this->created_at,
            'id'                  => $this->id,
            'completed'           => $this->completed,
        ];
    }
}
