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

$user = User::where('email', 'field.sales@solarerp.com')->orWhere('id', 4)->first();
if (! $user) {
    $user = User::where('is_active', true)->first();
}
if (! $user) { echo "no user\n"; exit(1); }
Auth::loginUsingId($user->id);
$app->make('session')->start();
View::share('errors', $app->make('session')->get('errors', new ViewErrorBag()));

// find a lead with lead_stage = 'new' and assigned to this user
// Try to find new lead assigned to user; if none, pick any 'new' lead and assign it
$lead = Lead::where('lead_stage', 'new')->where('assigned_user_id', $user->id)->first();
if (! $lead) {
    $lead = Lead::where('lead_stage', 'new')->first();
    if (! $lead) {
        echo json_encode(['ok'=>false,'error'=>'no new lead exists']);
        exit(1);
    }
    // assign to current user so the inline form would normally be visible
    $lead->assigned_user_id = $user->id;
    $lead->save();
}

$controller = new LeadController();
$results = ['lead_id' => $lead->id, 'before_stage' => $lead->lead_stage];

// render new leads and check presence
$req = Request::create('/leads/new', 'GET');
$res = $controller->newLeads($req);
$html = $res->render();
$results['present_before'] = (strpos($html, (string)$lead->id) !== false) || (strpos($html, $lead->name) !== false);

// call updateStage with same value
$reqPatch = Request::create('/leads/'.$lead->id.'/stage', 'PATCH', ['lead_stage' => $lead->lead_stage]);
$resp = $controller->updateStage($reqPatch, $lead);
$leadFresh = $lead->fresh();
$results['after_same_stage'] = $leadFresh->lead_stage;

// render new leads again
$res2 = $controller->newLeads($req);
$html2 = $res2->render();
$results['present_after_same'] = (strpos($html2, (string)$lead->id) !== false) || (strpos($html2, $lead->name) !== false);

// call updateStage with different stage 'contacted'
$reqPatch2 = Request::create('/leads/'.$lead->id.'/stage', 'PATCH', ['lead_stage' => 'contacted']);
$resp2 = $controller->updateStage($reqPatch2, $leadFresh);
$leadFresh2 = $leadFresh->fresh();
$results['after_contacted'] = $leadFresh2->lead_stage;

// render new leads again
$res3 = $controller->newLeads($req);
$html3 = $res3->render();
$results['present_after_contacted'] = (strpos($html3, (string)$lead->id) !== false) || (strpos($html3, $lead->name) !== false);

echo json_encode($results, JSON_PRETTY_PRINT);
