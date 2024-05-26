<?php

namespace App\Repositories\Event;

use App\Imports\EventsImport;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

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
            ->orderBy('id', 'desc')
            ->when(isset($filters['search_text']), function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search_text'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                if ($filters['status'] == 'complete') {
                    $query->where(function ($query) {
                        $query->where('completed', true);
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

    public function import(UploadedFile $file): array
    {
        $import = new EventsImport();

        // Import events from the CSV file
        Excel::import($import, $file);

        // Retrieve the imported events
        return $import->insertedEvents;
    }

    public function updateStatus($eventId)
    {
        $event = $this->model->findOrFail($eventId);

        if ($event->completed) {
            return false;
        }

        $event->completed = 1;
        $event->save();

        return $event;
    }
}
