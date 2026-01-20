<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$lead = App\Models\Lead::find(280);
echo 'Before: ' . ($lead->lead_stage ?? '(null)') . "\n";

$user = App\Models\User::find(4);
Illuminate\Support\Facades\Auth::loginUsingId(4);

$request = Illuminate\Http\Request::create('/leads/280/stage', 'PATCH', ['lead_stage' => 'negotiation']);
$request->setUserResolver(function() use ($user) { return $user; });

$controller = new App\Http\Controllers\LeadController();
$controller->updateStage($request, $lead);

$lead = App\Models\Lead::find(280);
echo 'After: ' . ($lead->lead_stage ?? '(null)') . "\n";
