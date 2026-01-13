<?php

namespace App\Support;

use App\Models\AdminSetting;
use Illuminate\Support\Facades\Auth;

class ViewerMode
{
    public static function enabled(): bool
    {
        return AdminSetting::isViewerModeEnabled();
    }

    public static function active(): bool
    {
        return session('viewer_mode') === true;
    }

    public static function isViewer(): bool
    {
        return Auth::user() instanceof ViewerUser;
    }
}
