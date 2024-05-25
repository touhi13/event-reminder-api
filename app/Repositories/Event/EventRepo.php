<?php

namespace App\Repositories\Event;

use App\Models\Event;
use Carbon\Carbon;

class EventRepo implements EventInterface
{
    protected Event $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function index($filters = [])
    {
        $query = $this->model
            ->orderBy('created_at', 'desc')
            ->when(isset($filters['search_text']), function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search_text'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                if ($filters['status'] == 'complete') {
                    $query->where(function ($query) {
                        $query->where('event_date', '<', Carbon::now())
                            ->orWhere('completed', true);
                    });
                } elseif ($filters['status'] == 'upcoming') {
                    $query->where('event_date', '>=', Carbon::now())
                        ->where('completed', false);
                }
            });

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function store(array $data)
    {
        $event = new Event();
        $event->fill($data);
        $event->save();

        return $event;
    }

    public function show($eventId)
    {
        return $this->model->findOrFail($eventId);
    }

    public function update($eventId, array $data)
    {
        $event = $this->model->findOrFail($eventId);
        $event->fill($data);
        $event->save();

        return $event;
    }

    public function destroy($eventId)
    {
        $event = $this->model->findOrFail($eventId);
        $event->delete();
    }
}
