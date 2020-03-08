<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    /**
     * Relation many to one to users table
     *
     * @return  BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\User');
    }
}
