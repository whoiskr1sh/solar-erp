<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$lead = App\Models\Lead::first();
if (!$lead) { echo "No lead found\n"; exit(1); }
echo 'Lead #' . $lead->id . ' stage=' . $lead->lead_stage . "\n";
