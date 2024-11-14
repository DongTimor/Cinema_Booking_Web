<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'birth_date',
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

    public function point()
    {
        return $this->hasOne(Point::class);
    }

    public function vouchers(): BelongsToMany
    {
        return $this->belongsToMany(Voucher::class)->withPivot('status');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($customer) {
            Point::create([
                'customer_id' => $customer->id,
                'total_points' => 0,
                'points_earned' => 0,
                'points_redeemed' => 0,
                'ranking_level' => 'Bronze',
                'last_updated' => now(),
            ]);
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
