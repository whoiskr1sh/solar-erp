<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lead;

$leads = Lead::orderBy('created_at', 'desc')
    ->take(10)
    ->get(['id','name','email','phone','assigned_user_id','created_by','status','lead_stage','is_reassigned']);

echo $leads->toJson(JSON_PRETTY_PRINT);
