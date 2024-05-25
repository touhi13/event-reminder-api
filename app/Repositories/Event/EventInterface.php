<?php

namespace App\Repositories\Event;

interface EventInterface
{
    public function index(array $data);
    public function store(array $data);
    public function show($eventId);
    public function update($eventId, array $data);
    public function destroy($eventId);
}
