<?php

namespace App\Repositories\Event;
use Illuminate\Http\UploadedFile;
interface EventInterface
{
    public function index(array $data);
    public function store(array $data);
    public function show($eventId);
    public function update($eventId, array $data);
    public function destroy($eventId);
    public function import(UploadedFile $file): array;
    public function updateStatus($eventId);
}
