<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_id',
        'name_en',
        'slug',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all artifacts for this category
     */
    public function artifacts(): HasMany
    {
        return $this->hasMany(Artifact::class);
    }

    /**
     * Get the localized name
     */
    public function getLocalizedName(): string
    {
        $locale = app()->getLocale();
        return $locale === 'id' ? $this->name_id : $this->name_en;
    }
}
