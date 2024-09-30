<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditorium extends Model
{
    use HasFactory;
    protected $table = 'auditoriums';

    protected $fillable = [
        'name'
    ];
    public function seats():HasMany
    {
        return $this->hasMany(Seat::class);
    }
    public function showtimes():HasMany
    {
        return $this->hasMany(Showtime::class);
    }
}
