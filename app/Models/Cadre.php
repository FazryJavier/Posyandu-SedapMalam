<?php

namespace App\Models;

use App\Enums\CadreTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cadre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'tier', 'position', 'phone', 'address', 'joined_on',
        'is_active', 'show_on_website', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'tier' => CadreTier::class,
            'joined_on' => 'date',
            'is_active' => 'boolean',
            'show_on_website' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
