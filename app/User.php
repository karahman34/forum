<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation one to many to posts table
     *
     * @return  HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
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

    /**
     * Relation many to mant to posts table
     *
     * @return  BelongsToMany
     */
    public function savedPosts()
    {
        return $this->belongsToMany('App\Post', 'saved_posts')->withTimestamps();
    }

    /**
     * Get formatted avatar url
     *
     * @return  string
     */
    public function getAvatar()
    {
        $defaultAvatar = 'img/avatars/default.png';

        return $this->avatar === null
            ? Storage::url($defaultAvatar)
            : Storage::url($this->avatar);
    }
}
