<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'flag_deleted',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function($permission){
            Dashboard::create([
                'activity' => "Created permission: {$permission->name}",
                'url' => route('permissions.show',['id' => $permission->id])
            ]);
        });

        static::updated(function($permission){
            Dashboard::create([
                'activity' => "Updated permission: {$permission->name}",
                'url' => route('permissions.show',['id' => $permission->id])
            ]);
        });

        static::deleted(function($permission){
            Dashboard::create([
                'activity' => "Deleted permission : {$permission->name}"
            ]);
        });
    }
}
