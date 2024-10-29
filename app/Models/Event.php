<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'all_day',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'discount_percentage',
        'number_of_tickets',
        'quantity',
    ];

    protected $guarded = ['id'];
}
