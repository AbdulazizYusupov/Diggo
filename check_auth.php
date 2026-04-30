<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = Illuminate\Support\Facades\Auth::user();
if($user) {
    echo 'Auth User ID: ' . $user->id . "\n";
    echo 'Auth User Name: ' . $user->name . "\n";
    echo 'Roles: ';
    foreach($user->roles as $role) {
        echo $role->name . ', ';
    }
    echo "\n";
    echo 'hasRole admin: ' . ($user->hasRole('admin') ? 'true' : 'false') . "\n";
    echo 'Session impersonate.active: ' . (session('impersonate.active') ? 'true' : 'false') . "\n";
    if(session('impersonate.active')) {
        echo 'Original User ID: ' . session('impersonate.original_id') . "\n";
    }
} else {
    echo 'No auth user' . "\n";
}