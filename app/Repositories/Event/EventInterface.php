<?php

namespace App\Repositories\Event;

interface EventInterface
{
    public function all(array $data);
    public function save(array $data);
    public function manage($data, $id);
    public function eventsCounts();
}