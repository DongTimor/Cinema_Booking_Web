<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'points_required',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_voucher');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_voucher')->withPivot('status');
    }
}
