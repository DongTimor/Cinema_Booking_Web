<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trailer',
        'start_time',
        'end_time',
        'duration',
        'status',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_id');
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }
}
