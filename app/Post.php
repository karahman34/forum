<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title', 'body'
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
     * Relation one to many to post_images table
     *
     * @return  HasMany
     */
    public function images()
    {
        return $this->hasMany('App\PostImage');
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
}
