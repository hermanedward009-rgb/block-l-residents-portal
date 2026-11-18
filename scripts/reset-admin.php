<?php

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = $argv[1] ?? 'admin@blockl.local';
$password = $argv[2] ?? 'BlockLAdmin2026!';

User::query()->updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Block L Administrator',
        'phone' => '+255000000000',
        'room_number' => null,
        'password' => Hash::make($password),
        'role' => UserRole::Admin,
        'status' => AccountStatus::Approved,
        'approved_at' => now(),
    ],
);

$verified = Hash::check($password, User::query()->where('email', $email)->value('password'));

echo $verified
    ? "Admin reset OK: {$email}\n"
    : "Admin reset FAILED for {$email}\n";

exit($verified ? 0 : 1);