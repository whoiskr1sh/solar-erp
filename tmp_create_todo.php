<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::find(4);
if (!$user) { echo "User 4 not found\n"; exit(1); }
Illuminate\Support\Facades\Auth::loginUsingId(4);

// create todo
$todo = App\Models\Todo::create([
    'title' => 'smoke test todo ' . time(),
    'description' => 'created by smoke test',
    'task_date' => date('Y-m-d'),
    'status' => 'pending',
    'is_carried_over' => 0,
    'user_id' => $user->id,
]);

echo "Created todo id={$todo->id}\n";

$rows = App\Models\Todo::where('user_id', $user->id)->orderByDesc('id')->limit(5)->get();
foreach ($rows as $r) {
    echo "- {$r->id} {$r->title} date={$r->task_date} status={$r->status} carried={$r->is_carried_over}\n";
}
