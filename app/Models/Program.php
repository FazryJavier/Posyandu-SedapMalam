<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['icon', 'title', 'description', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
