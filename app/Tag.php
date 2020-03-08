<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Relation many to many to posts table
     *
     * @return  BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post', 'post_tags');
    }
}
