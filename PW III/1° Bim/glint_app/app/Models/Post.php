<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['subject', 'image', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy(){

        return $this->belongsToMany(User::class, 'post_user_likes')->withTimestamps();

    }

    public function comments(){

        return $this->hasMany(Comment::class)->orderByDesc('created_at');

    }

}
