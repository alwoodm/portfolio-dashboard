<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /**
     * Content page display names mapping.
     */
    protected static array $pageNames = [
        'home' => 'Home',
        'about' => 'About',
        'skills' => 'Skills',
        'career' => 'Career',
        'projects' => 'Projects',
        'contact' => 'Contact',
        'seo' => 'SEO',
    ];

    protected $fillable = [
        'user_id',
        'content_page',
        'action',
        'is_viewer',
    ];

    protected $casts = [
        'is_viewer' => 'boolean',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get recent activity logs.
     */
    public function scopeRecent(Builder $query, int $limit = 10): Builder
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by content page.
     */
    public function scopeByPage(Builder $query, string $page): Builder
    {
        return $query->where('content_page', $page);
    }

    /**
     * Get the formatted page name.
     */
    public function getPageNameAttribute(): string
    {
        return self::$pageNames[$this->content_page] ?? ucfirst($this->content_page);
    }

    /**
     * Get the formatted action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'updated' => 'Updated',
            'created' => 'Created',
            'deleted' => 'Deleted',
            default => ucfirst($this->action),
        };
    }
}
