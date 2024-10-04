<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditorium extends Model
{
    use HasFactory;
    protected $table = 'auditoriums';

    protected $fillable = [
        'name',
        'total'
    ];
    public function seats():HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes():HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($auditorium) {
            Dashboard::create([
                'activity' => "Created auditorium: {$auditorium->name}", 
                'url' => route('auditoriums.show', ['id' => $auditorium->id]),
            ]);
        });

        static::updated(function ($auditorium) {
            if ($auditorium->isDirty('name') || $auditorium->isDirty('total')) {
                $activity = "Updated {$auditorium->getOriginal('name')} auditorium:";
                if ($auditorium->isDirty('name')) {
                    $activity .= "name from {$auditorium->getOriginal('name')} to {$auditorium->name}";
                }
                if ($auditorium->isDirty('total')) {
                    if ($auditorium->isDirty('name')) {
                        $activity .= ", ";
                    }
                    $activity .= "total from {$auditorium->getOriginal('total')} to {$auditorium->total}";
                }
                Dashboard::create([
                    'activity' => $activity, 
                    'url' => route('auditoriums.show', ['id' => $auditorium->id]) // Generate URL for the updated auditorium
                ]);
            }
        });

        static::deleted(function ($auditorium) {
            Dashboard::create([
                'activity' => "Deleted auditorium: {$auditorium->name}", // Log auditorium deletion
            ]);
        });
    }
}
