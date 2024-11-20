<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trailer',
        'start_date',
        'end_date',
        'duration',
        'status',
        'price',
        'event_id',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function showtimes(): BelongsToMany
    {
        return $this->belongsToMany(Showtime::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }


    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function($movie){
            Dashboard::create([
                'user_id' => auth()->id(),
                'activity' => "Created movie: {$movie->name}",
                'url' => route('movies.features.show',['id' => $movie->id]),
            ]);
        });

        static::updated(function($movie){
            Dashboard::create([
                'user_id' => auth()->id(),
                'activity' => "Updated {$movie->getOriginal('name')} movie: name from {$movie->getOriginal('name')} to {$movie->name}",
                'url' => route('movies.features.show',['id' => $movie->id])
            ]);
        });

        static::deleted(function($movie){
            Dashboard::create([
                'user_id' => auth()->id(),
                'activity' => "Deleted movie: {$movie->name} "
            ]);
        });
    }

}
