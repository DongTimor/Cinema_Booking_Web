<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'gender',
        'date_of_birth',
        'email',
        'password',
        'image',
        'status'
    ];

    protected $guarded = ['id'];
    protected $hidden = ['password'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
