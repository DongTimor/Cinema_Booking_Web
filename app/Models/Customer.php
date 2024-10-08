<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone_number', 'address', 'gender', 'date_of_birth', 'email'];
    protected $guarded = ['id'];

    function tickets():HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
