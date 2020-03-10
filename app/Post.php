<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body'
    ];

    /**
     * Relation many to one to users table
     *
     * @return  BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     *
     * Get all of the post's images.
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }

    /**
     * Relation many to many to tags table
     *
     * @return  BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'post_tags');
    }

    /**
     * Relation one to one to post_seens table
     *
     * @return  HasOne
     */
    public function seen()
    {
        return $this->hasOne('App\PostSeen');
    }

    /**
     * Relation one to many to comments table.
     *
     * @return  HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
