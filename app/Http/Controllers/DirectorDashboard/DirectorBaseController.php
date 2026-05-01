<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Support\Facades\Auth;

abstract class DirectorBaseController extends Controller
{
    protected function director(): Director
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole('director') || ! $user->director) {
            abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q!');
        }

        return $user->director;
    }
}
