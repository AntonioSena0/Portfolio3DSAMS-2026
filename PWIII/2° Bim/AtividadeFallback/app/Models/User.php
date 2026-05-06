<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password"];

    public function tasks(){

        return $this->hasMany(Task::class)->orderByDesc("created_at");

    }

}
