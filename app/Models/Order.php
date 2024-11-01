<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'movie',
        'start_time',
        'end_time',
        'price',
        'auditorium',
        'voucher',
        'quantity',
        'ticket_ids',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
