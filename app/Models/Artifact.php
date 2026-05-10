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
        'name',
        'title_id',
        'title_en',
        'description',
        'description_id',
        'description_en',
        'image',
        'image_path',
        'keywords',
        'material',
        'cultural_significance',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'is_published' => 'boolean',
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

        return $this->title_id ?? $this->name;
    }

    /**
     * Get the localized description
     */
    public function getLocalizedDescription(): string
    {
        $locale = app()->getLocale();

        return $this->description_id ?? $this->description ?? '';
    }

    /**
     * Get the full image path URL
     */
    public function getImageUrl(): string
    {
        return asset('storage/' . ($this->image_path ?? $this->image));
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

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['title_id'] = $value;
        $this->attributes['title_en'] = $value;
    }

    public function setTitleIdAttribute($value): void
    {
        $this->attributes['title_id'] = $value;
        if (! isset($this->attributes['name'])) {
            $this->attributes['name'] = $value;
        }
    }

    public function setTitleEnAttribute($value): void
    {
        $this->attributes['title_en'] = $value;
    }

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = $value;
        $this->attributes['description_id'] = $value;
        $this->attributes['description_en'] = $value;
    }

    public function setDescriptionIdAttribute($value): void
    {
        $this->attributes['description_id'] = $value;
        if (! isset($this->attributes['description'])) {
            $this->attributes['description'] = $value;
        }
    }

    public function setDescriptionEnAttribute($value): void
    {
        $this->attributes['description_en'] = $value;
    }

    public function setImageAttribute($value): void
    {
        $this->attributes['image'] = $value;
        $this->attributes['image_path'] = $value;
    }

    public function setImagePathAttribute($value): void
    {
        $this->attributes['image_path'] = $value;
        $this->attributes['image'] = $value;
    }

    public function setIsPublishedAttribute($value): void
    {
        $this->attributes['is_published'] = $value;
        $this->attributes['is_featured'] = $value;
    }

    public function setIsFeaturedAttribute($value): void
    {
        $this->attributes['is_featured'] = $value;
        $this->attributes['is_published'] = $value;
    }
}
