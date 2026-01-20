<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// login as user id 4
$user = App\Models\User::find(4);
if (!$user) { echo "User 4 not found\n"; exit(1); }
Illuminate\Support\Facades\Auth::loginUsingId(4);

$lead = App\Models\Lead::first();
if (!$lead) { echo "No lead found\n"; exit(1); }

// Build request
$request = Illuminate\Http\Request::create('/leads/'.$lead->id.'/stage', 'PATCH', ['lead_stage' => 'site_survey_done']);
$request->setUserResolver(function() use ($user) { return $user; });

$controller = new App\Http\Controllers\LeadController();
$response = $controller->updateStage($request, $lead);
if ($response instanceof Illuminate\Http\JsonResponse) {
    echo "JSON: "; print_r($response->getData());
} else {
    echo "Redirect or HTML response\n";
}
