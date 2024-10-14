<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function($category){
            Dashboard::create([
                'user_id' => auth()->id(), 
                'activity' => "Created category: {$category->name}",
                'url' => route('movies.categories.show',['id' => $category->id]),
            ]);
        });

        static::updated(function($category){
            Dashboard::create([
                'user_id' => auth()->id(), 
                'activity' => "Updated {$category->getOriginal('name')} category: name from {$category->getOriginal('name')} to {$category->name}",
                'url' => route('movies.categories.show',['id' => $category->id])
            ]);
        });

        static::deleted(function($category){
            Dashboard::create([
                'user_id' => auth()->id(), 
                'activity' => "Deleted category: {$category->name} "
            ]);
        });
    }
}
