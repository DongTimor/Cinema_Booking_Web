<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'flag_deleted'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function assignRole($name)
    {
        $role = Role::where('name',$name)->first();

        return $role->id;
    }

    public function updatePermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }

    public function updateRole(array $data)
    {
        $this->fill($data);
        return $this->save();
    }

    protected static function boot() 
    {
        parent::boot();

        static::created(function ($role) {
            Dashboard::create([
                'user_id' => auth()->id(), 
                'activity' => "Created role: {$role->name}",
                'url' => route('roles.show',['id' => $role->id])
            ]);
        });

        static::updated(function ($role) {
            Dashboard::created([
                'user_id' => auth()->id(), 
                'activity' => "Updated {$role->name} role",
                'url' => route('roles.show',['id' => $role->id])
            ]);
        });

        static::deleted(function ($role){
            Dashboard::created([
                'user_id' => auth()->id(), 
                'activity' => "Deleted {$role} role"
            ]);
        });
    }
}
