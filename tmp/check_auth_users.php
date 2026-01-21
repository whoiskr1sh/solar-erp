<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$emails = ['field.sales@solarerp.com', 'superadmin@solarerp.com'];
$result = [];

foreach ($emails as $email) {
    $u = User::where('email', $email)->first();
    if (! $u) {
        $result[$email] = null;
        continue;
    }

    $result[$email] = [
        'id' => $u->id,
        'email' => $u->email,
        'is_active' => $u->is_active ?? null,
        'deleted_at' => $u->deleted_at ?? null,
        'password_hash' => $u->password ?? null,
        'hash_matches_password123' => Hash::check('password123', $u->password ?? ''),
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);
