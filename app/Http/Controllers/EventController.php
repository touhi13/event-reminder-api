<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\Models\Event;
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
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'event_date'            => 'required|date',
            'reminder_recipients'   => 'nullable|array',
            'reminder_recipients.*' => 'email',
        ]);

        $event = Event::create($validated);

        // Dispatch the email reminder job
        $delay = now()->diffInSeconds($event->event_date);
        SendReminderEmail::dispatch($event)->delay($delay);

        return response()->json($event, 201);
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
