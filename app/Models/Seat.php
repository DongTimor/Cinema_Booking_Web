<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = ['auditorium_id', 'seat_number'];
    protected $guarded = ['id'];

    public function auditorium(): BelongsTo
    {
        return $this->belongsTo(Auditorium::class);
    }

}


