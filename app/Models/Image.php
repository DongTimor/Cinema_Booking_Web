<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'url', 'type'];

    public function movies(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
