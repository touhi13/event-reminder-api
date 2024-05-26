<?php

namespace App\Imports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EventsImport implements ToModel, WithStartRow
{
    public $insertedEvents = [];

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Skip the first row (header)
    }

    /**
     * @param array $row
     * @return Event|null
     */
    public function model(array $row)
    {
        // Check if the row has all necessary fields
        if (count($row) != 5) {
            return null;
        }

        // Create a new Event instance
        $event = new Event([
            'title'               => $row[0],
            'description'         => $row[1],
            'event_date'          => $row[2],
            'reminder_recipients' => explode(',', $row[3]),
            'completed'           => $row[4],
        ]);

        $event->save();

        // Add the event to the insertedEvents array
        $this->insertedEvents[] = $event;

        return $event;
    }
}
