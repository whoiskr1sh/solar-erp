<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\LeadController;
use App\Models\User;
use Illuminate\Support\ViewErrorBag;

$user = User::where('email', 'superadmin@solarerp.com')->orWhere('id', 4)->first();
if (!$user) {
    echo "No test user found (superadmin@solarerp.com or id=4).\n";
    exit(1);
}

Auth::loginUsingId($user->id);

// Ensure session is started and views have the usual shared data (like $errors)
$app->make('session')->start();
View::share('errors', $app->make('session')->get('errors', new ViewErrorBag()));

$controller = new LeadController();

$results = [];

try {
    $req = Request::create('/leads', 'GET', ['view' => 'all']);
    $response = $controller->index($req);
    $html = $response->render();
    $results['all'] = ['ok' => true, 'length' => strlen($html)];
} catch (\Throwable $e) {
    $results['all'] = ['ok' => false, 'error' => (string)$e];
}

try {
    $req2 = Request::create('/leads/new', 'GET');
    $response2 = $controller->newLeads($req2);
    $html2 = $response2->render();
    $results['new'] = ['ok' => true, 'length' => strlen($html2)];
} catch (\Throwable $e) {
    $results['new'] = ['ok' => false, 'error' => (string)$e];
}

echo json_encode($results, JSON_PRETTY_PRINT);
