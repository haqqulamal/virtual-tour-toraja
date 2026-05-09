<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artifact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title_id',
        'title_en',
        'description_id',
        'description_en',
        'image_path',
        'material',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category this artifact belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the localized title
     */
    public function getLocalizedTitle(): string
    {
        $locale = app()->getLocale();
        return $locale === 'id' ? $this->title_id : $this->title_en;
    }

    /**
     * Get the localized description
     */
    public function getLocalizedDescription(): string
    {
        $locale = app()->getLocale();
        return $locale === 'id' ? $this->description_id : $this->description_en;
    }

    /**
     * Get the full image path URL
     */
    public function getImageUrl(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Scope to get only featured artifacts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
