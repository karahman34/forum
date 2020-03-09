<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSeen extends Model
{
    protected $fillable = [
        'post_id', 'count'
    ];

    /**
     * Relation one to one to posts table
     *
     * @return  BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
