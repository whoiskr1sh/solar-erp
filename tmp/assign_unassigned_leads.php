<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lead;
use Illuminate\Support\Facades\DB;

$leads = Lead::whereNull('assigned_user_id')
    ->whereNotNull('created_by')
    ->get(['id','name','created_by']);

$count = $leads->count();

if ($count === 0) {
    echo "No unassigned leads found.\n";
    exit(0);
}

$ids = $leads->pluck('id')->toArray();

foreach ($leads as $lead) {
    $lead->assigned_user_id = $lead->created_by;
    $lead->save();
}

echo "Assigned {$count} lead(s) to their creators.\n";
echo "Sample updated leads:\n";
$sample = Lead::whereIn('id', array_slice($ids,0,10))->get(['id','name','assigned_user_id','created_by']);
echo $sample->toJson(JSON_PRETTY_PRINT);
