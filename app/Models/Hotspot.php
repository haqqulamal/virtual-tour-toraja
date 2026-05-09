<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'scene_id',
        'target_scene_id',
        'type',
        'pitch',
        'yaw',
        'title',
        'content',
        'image_path',
    ];

    protected $casts = [
        'pitch' => 'float',
        'yaw' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the scene this hotspot belongs to
     */
    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    /**
     * Get the target scene for scene-type hotspots
     */
    public function targetScene(): BelongsTo
    {
        return $this->belongsTo(Scene::class, 'target_scene_id');
    }

    /**
     * Get the full image path URL if exists
     */
    public function getImageUrl(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    /**
     * Scope to get hotspots by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get info-type hotspots
     */
    public function scopeInfoType($query)
    {
        return $query->where('type', 'info');
    }

    /**
     * Scope to get scene-type hotspots (navigation)
     */
    public function scopeSceneType($query)
    {
        return $query->where('type', 'scene');
    }
}
