<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\Repositories\Event\EventInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventController extends Controller
{

    use ApiResponseTrait;

    private EventInterface $repository;

    public function __construct(EventInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->repository->index($request->all());
        return $this->ResponseSuccess($data, null, 'events retrieved successfully', 200, 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'event_date'            => 'required|date',
            'reminder_recipients'   => 'nullable|array',
            'reminder_recipients.*' => 'email',
        ]);

        $data = $this->repository->store($validatedData);

        // Dispatch the email reminder job
        $emailData = [
            'subject'    => "Event Reminder | $data->event_id",
            'email_body' => view('emails.reminder', compact('data'))->render(),
            'to'         => $data->reminder_recipients,
        ];

        $delay = now()->diffInSeconds($data->event_date);

        SendReminderEmail::dispatch($emailData)->delay($delay);

        return $this->ResponseSuccess($data, null, 'event created successfully', 201, 'success');

    }

    /**
     * Display the specified resource.
     */
    public function show($eventId)
    {
        $event = $this->repository->show($eventId);
        return $this->ResponseSuccess($event, null, 'event retrieved successfully', 200, 'success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $eventId)
    {
        $validatedData = $request->validate([
            'title'                 => 'sometimes|string|max:255',
            'description'           => 'nullable|string',
            'event_date'            => 'sometimes|date',
            'reminder_recipients'   => 'nullable|array',
            'reminder_recipients.*' => 'email',
        ]);

        $event = $this->repository->update($eventId, $validatedData);
        return $this->ResponseSuccess($event, null, 'event updated successfully', 200, 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($eventId)
    {
        $this->repository->destroy($eventId);
        return $this->ResponseSuccess(null, null, 'event deleted successfully', 200, 'success');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $importedEvents = $this->repository->import($request->file('file'));

        // Trigger email reminders for each imported event
        foreach ($importedEvents as $data) {
            $emailData = [
                'subject'    => "Event Reminder | $data->event_id",
                'email_body' => view('emails.reminder', compact('data'))->render(),
                'to'         => $data->reminder_recipients,
            ];

            $delay = now()->diffInSeconds($data->event_date);

            SendReminderEmail::dispatch($emailData)->delay($delay);
        }

        return $this->ResponseSuccess($importedEvents, null, 'Events imported successfully', 200, 'success');
    }

    public function updateStatus(Request $request, $eventId)
    {
        $event = $this->repository->updateStatus($eventId);

        if (!$event) {
            return $this->ResponseError(null, null, 'Event is already complete and cannot be changed.', 400);
        }

        return $this->ResponseSuccess($event, null, 'Event status updated successfully');
    }

}
