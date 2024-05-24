<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\Models\Event;
use App\Repositories\Event\EventInterface;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
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
    public function index()
    {
        //
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
        // Ensure event_date is a Carbon instance and both dates are in the same timezone
        $eventDate = Carbon::parse($data->event_date)->setTimezone('UTC');
        $now       = Carbon::now()->setTimezone('UTC');

        $delay = $eventDate->isFuture() ? $now->diffInSeconds($eventDate) : 0;

        $data->delay = $delay;
        $data->now   = $now->toISOString();

        SendReminderEmail::dispatch($emailData)->delay($delay);

        return $this->ResponseSuccess($data, null, 'event created successfully', 201, 'success');

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
