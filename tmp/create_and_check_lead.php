<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use App\Http\Controllers\LeadController;
use App\Models\User;
use App\Models\Lead;

// find test user (prefer field.sales email or id 4)
$user = User::where('email', 'field.sales@solarerp.com')
    ->orWhere('email', 'superadmin@solarerp.com')
    ->orWhere('id', 4)
    ->first();

if (! $user) {
    $user = User::where('is_active', true)->first();
}

if (! $user) {
    echo json_encode(['ok' => false, 'error' => 'No suitable user found']);
    exit(1);
}

Auth::loginUsingId($user->id);
$app->make('session')->start();
View::share('errors', $app->make('session')->get('errors', new ViewErrorBag()));

// Create a unique test lead
$timestamp = date('YmdHis');
$leadName = "Test Lead {$timestamp}";
$leadEmail = "test-lead-{$timestamp}@example.local";

$leadData = [
    'name' => $leadName,
    'email' => $leadEmail,
    'phone' => '999999' . substr($timestamp, -4),
    'company' => 'TestCo',
    'address' => '123 Test Lane',
    'city' => 'Testville',
    'state' => 'TS',
    'pincode' => '560001',
    'source' => 'other',
    // DB status enum may not include 'new' — use 'interested' so insertion succeeds
    'status' => 'interested',
    'lead_stage' => 'new',
    'priority' => 'medium',
    'notes' => 'Created by automated test script',
    'assigned_user_id' => $user->id,
    'created_by' => $user->id,
];

try {
    $lead = Lead::create($leadData);
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => (string)$e]);
    exit(1);
}

$controller = new LeadController();
$results = ['created' => ['ok' => true, 'id' => $lead->id, 'name' => $leadName, 'email' => $leadEmail]];

// Render /leads
try {
    $req = Request::create('/leads', 'GET');
    $response = $controller->index($req);
    $html = $response->render();
    $found = (strpos($html, $leadName) !== false) || (strpos($html, $leadEmail) !== false);
    $results['all'] = ['ok' => true, 'length' => strlen($html), 'found' => $found];
} catch (\Throwable $e) {
    $results['all'] = ['ok' => false, 'error' => (string)$e];
}

// Render New Leads (use the dedicated method which checks lead_stage = 'new')
try {
    $req2 = Request::create('/leads/new', 'GET');
    $response2 = $controller->newLeads($req2);
    $html2 = $response2->render();
    $found2 = (strpos($html2, $leadName) !== false) || (strpos($html2, $leadEmail) !== false);
    $results['new'] = ['ok' => true, 'length' => strlen($html2), 'found' => $found2];
} catch (\Throwable $e) {
    $results['new'] = ['ok' => false, 'error' => (string)$e];
}

echo json_encode($results, JSON_PRETTY_PRINT);
