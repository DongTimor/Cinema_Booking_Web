<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_time',
        'end_time'
    ];
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
    public function movie()
    {
        return $this->hasOneThrough(Movie::class, Schedule::class, 'movie_id', 'id', 'id', 'movie_id');
    }
    public function auditorium(): BelongsTo
    {
        return $this->belongsTo(Auditorium::class);
    }
    public function schedules():BelongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }
}
