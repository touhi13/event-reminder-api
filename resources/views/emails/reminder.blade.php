<!DOCTYPE html>
<html>
<head>
    <title>Event Reminder</title>
</head>
<body>
    <h1>Event Reminder</h1>
    <p>Don't forget about the event: {{ $data->title }}</p>
    <p>Date: {{ $data->event_date }}</p>
    <p>Description: {{ $data->description }}</p>
</body>
</html>
