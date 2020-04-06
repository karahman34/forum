<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifCount extends Model
{
    protected $fillable = [
        'user_id', 'count'
    ];

    public $timestamps = false;

    protected $table = 'notification_count';
}
