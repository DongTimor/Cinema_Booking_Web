<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'description',
        'quantity',
        'expires_at',
        'value',
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_voucher', 'voucher_id', 'user_id');
    }
}
