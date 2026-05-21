<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = ['username', 'bio', 'email', 'password'];

    protected $hidden = ['email', 'password'];

    public function posts(){

        return $this->hasMany(Post::class);

    }

    public function likedPosts(){

        return $this->belongsToMany(Post::class, 'post_user_likes')->withTimestamps();

    }

    public function comments(){

        return $this->hasMany(Comment::class)->orderByDesc('created_at');

    }

    public function following(){

        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();

    }

    public function followers(){

        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();

    }

}
