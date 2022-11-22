<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory;
    use HasSlug;
    
    protected $hidden = ['user_id', 'cat_id'];
    protected $table='posts';
    protected $fillable =[ 
        'title',
        'post',
        'cat_id',
        'user_id',
        'image',
        'views',
    ];

    public function getSlugOptions():SlugOptions { 
        return (new SlugOptions())
            ->generateSlugsFrom('title')
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
    

    public function tags() { 
        return $this->belongsToMany(Tag::class);
    }
    public function category() { 
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function user() { 
        return $this->belongsTo(User::class, 'user_id');
    }
}