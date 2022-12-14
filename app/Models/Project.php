<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table='projects';
    protected $fillable =[ 
        'name',
        'description',
        'image',
        'website',
        'design',
        'technologies',
    ];

    public function getSlugOptions():SlugOptions { 
        return (new SlugOptions())
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->usingSeparator('-');
    }

        /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'slug';
    }


    public function user() { 
        return $this->belongsTo(User::class, 'user_id');
    }
}