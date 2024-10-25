<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_points',
        'points_earned',
        'points_redeemed',
        'ranking_level',
        'last_updated',
        'date_expire',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
