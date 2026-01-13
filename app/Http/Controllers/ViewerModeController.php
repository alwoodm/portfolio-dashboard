<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ViewerModeController extends Controller
{
    public function start(): RedirectResponse
    {
        if (! AdminSetting::isViewerModeEnabled()) {
            abort(404);
        }

        Auth::logout();
        session()->put('viewer_mode', true);

        return redirect('/admin');
    }

    public function stop(): RedirectResponse
    {
        session()->forget('viewer_mode');

        return redirect('/admin/login');
    }
}
