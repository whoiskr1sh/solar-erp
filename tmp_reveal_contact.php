<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::find(4);
if (!$user) { echo "User 4 not found\n"; exit(1); }
Illuminate\Support\Facades\Auth::loginUsingId(4);

$lead = App\Models\Lead::find(280);
if (!$lead) { echo "Lead not found\n"; exit(1); }

$request = Illuminate\Http\Request::create('/leads/'.$lead->id.'/reveal-contact', 'POST', []);
$request->headers->set('Accept', 'application/json');
$request->setUserResolver(function() use ($user) { return $user; });
$app->instance('request', $request);
$controller = new App\Http\Controllers\LeadController();
$response = $controller->revealContact($lead);
if ($response instanceof Illuminate\Http\JsonResponse) {
    echo "JSON:\n";
    print_r($response->getData(true));
} else {
    echo "Not JSON response.\n";
}
