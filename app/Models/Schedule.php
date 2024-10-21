<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'date',
        'auditorium_id',
    ];

    protected $guarded = [
        'id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function auditoriums():BelongsToMany
    {
        return $this->belongsToMany(Auditorium::class);
    }

    public function showtimes():BelongsToMany
    {
        return $this->belongsToMany(Showtime::class);
    }
}

