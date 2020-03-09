<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    protected $fillable = [
        'post_id', 'image'
    ];

    /**
     * Relation many to one to posts table
     *
     * @return  BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    /**
     * Return full url image path
     *
     * @return  string
     */
    public function getImage()
    {
        return Storage::url($this->image);
    }
}
