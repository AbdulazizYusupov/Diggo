<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$group = App\Models\Group::first();
if($group) {
    echo 'Group ID: ' . $group->id . "\n";
    $policy = new App\Policies\AttendancePolicy();
    $user = App\Models\User::first();
    echo 'Policy view: ' . ($policy->view($user, $group) ? 'true' : 'false') . "\n";
    echo 'Policy update: ' . ($policy->update($user, $group) ? 'true' : 'false') . "\n";
} else {
    echo 'No groups found' . "\n";
}