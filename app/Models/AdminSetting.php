<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = [
        'viewer_mode_enabled',
    ];

    protected $casts = [
        'viewer_mode_enabled' => 'bool',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }

    public static function isViewerModeEnabled(): bool
    {
        return (bool) (static::current()->viewer_mode_enabled ?? false);
    }
}
