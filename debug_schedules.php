<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Space;
use App\Models\Schedule;

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$space = Space::find(4);
if (!$space) {
    echo "No space found with ID 4\n";
    exit;
}

echo "Testing Space ID: " . $space->id . " (" . $space->name . ")\n";

$schedules = Schedule::with(['trainer', 'group'])
    ->where('space_id', $space->id)
    ->orderBy('day_of_week')
    ->orderBy('start_time')
    ->get();

echo "Schedules count: " . $schedules->count() . "\n";
foreach($schedules as $s) {
    echo "- Subject: " . $s->subject . " | Trainer: " . ($s->trainer ? $s->trainer->first_name : 'NULL') . " | Group: " . ($s->group ? $s->group->name : 'NULL') . "\n";
}

echo "\nJSON Output:\n";
echo $schedules->toJson(JSON_PRETTY_PRINT);
