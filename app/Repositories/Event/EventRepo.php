<?php

namespace App\Repositories\Event;

use App\Models\Event;
use App\Repositories\Event\EventInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventRepo implements EventInterface
{
    protected Event $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }
    public function all($filters = [])
    {

        $query = $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->when(isset($filters['search_text']), function ($query) use ($filters) {
                $query->whereHas('user', function ($subQuery) use ($filters) {
                    $subQuery->where('name', 'like', '%' . $filters['search_text'] . '%');
                });
            })
            ->when(isset($filters['leave_type']), function ($query) use ($filters) {
                $query->where('leave_type', $filters['leave_type']);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            })
            ->when(auth()->user()->role === 'employee', function ($query) {
                $query->where('user_id', auth()->id());
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

    public function manage($data, $id)
    {
        $Event = Event::with('user')->find($id);

        if (!$Event) {
            return null; // Return null if leave request not found
        }

        if ($data['action'] === 'Approved' || $data['action'] === 'Rejected') {
            $Event->update([
                'status'        => $data['action'],
                'admin_comment' => $data['admin_comment'] ?? null,
            ]);
        } else {
            return null;
        }

        return $Event;
    }

    public function eventsCounts()
    {
        $result = DB::table('leave_requests')
            ->selectRaw('COUNT(*) as total_count,
                 SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                 SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count,
                 SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected_count')
            ->first();

            return $result;
    }

}