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
        'panorama_image',
        'image_path',
        'thumbnail',
        'location',
        'latitude',
        'longitude',
        'order',
        'is_published',
        'is_active',
    ];

    protected $casts = [
        'is_published' => 'boolean',
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
        return asset('storage/' . ($this->image_path ?? $this->panorama_image));
    }

    /**
     * Get the full thumbnail path URL
     */
    public function getThumbnailUrl(): string
    {
        return asset('storage/' . ($this->thumbnail ?? $this->image_path ?? $this->panorama_image));
    }

    /**
     * Scope to get only active scenes
     */
    public function scopeActive($query)
    {
        return $query->where(function ($subQuery) {
            $subQuery->where('is_active', true)
                ->orWhere('is_published', true);
        });
    }

    /**
     * Scope to order by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function setPanoramaImageAttribute($value): void
    {
        $this->attributes['panorama_image'] = $value;
        $this->attributes['image_path'] = $value;
    }

    public function setImagePathAttribute($value): void
    {
        $this->attributes['image_path'] = $value;
        $this->attributes['panorama_image'] = $value;
    }

    public function setIsPublishedAttribute($value): void
    {
        $this->attributes['is_published'] = $value;
        $this->attributes['is_active'] = $value;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['is_active'] = $value;
        $this->attributes['is_published'] = $value;
    }
}
