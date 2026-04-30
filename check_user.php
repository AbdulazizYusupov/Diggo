<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();
echo 'User ID: ' . $user->id . "\n";
echo 'Roles: ';
foreach($user->roles as $role) {
    echo $role->name . ', ';
}
echo "\n";
echo 'hasRole admin: ' . ($user->hasRole('admin') ? 'true' : 'false') . "\n";
echo 'hasRole super admin: ' . ($user->hasRole('super admin') ? 'true' : 'false') . "\n";
echo 'hasRole superadmin: ' . ($user->hasRole('superadmin') ? 'true' : 'false') . "\n";