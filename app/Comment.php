<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'body',
    ];

    /**
     * Relation many to one to posts table.
     *
     * @return  BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    /**
     * Relation many to one to users table.
     *
     * @return  BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get all of the comments's images.
     *
     * @return  MorphMany
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }
}
