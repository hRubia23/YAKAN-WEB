<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class YakanPattern extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'difficulty_level',
        'pattern_data',
        'base_color',
        'color_variations',
        'base_price_multiplier',
        'is_active',
        'popularity_score',
    ];

    protected $casts = [
        'pattern_data' => 'array',
        'color_variations' => 'array',
        'base_price_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function customOrders(): HasMany
    {
        return $this->hasMany(CustomOrder::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PatternMedia::class)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PatternTag::class, 'pattern_tag_pivot', 'yakan_pattern_id', 'pattern_tag_id');
    }

    public function getDifficultyColorAttribute(): string
    {
        $colors = [
            'simple' => 'green',
            'medium' => 'yellow',
            'complex' => 'red',
        ];

        return $colors[$this->difficulty_level] ?? 'gray';
    }

    public function getEstimatedDaysAttribute(): int
    {
        $days = [
            'simple' => 7,
            'medium' => 14,
            'complex' => 21,
        ];

        return $days[$this->difficulty_level] ?? 14;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function incrementPopularity(): void
    {
        $this->increment('popularity_score');
    }
}
