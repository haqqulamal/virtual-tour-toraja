<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scene extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'thumbnail',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all hotspots for this scene
     */
    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }

    /**
     * Get the full image path URL
     */
    public function getImageUrl(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the full thumbnail path URL
     */
    public function getThumbnailUrl(): string
    {
        return asset('storage/' . $this->thumbnail);
    }

    /**
     * Scope to get only active scenes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
