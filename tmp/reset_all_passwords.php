<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all();
$count = 0;
foreach ($users as $u) {
    $u->password = bcrypt('password123');
    $u->save();
    $count++;
}

echo "Updated {$count} user(s)\n";
