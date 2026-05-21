<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = ["title", "end_at", "user_id", "is_complete"];

    protected $casts = [
        "end_at" => "datetime",
        "is_complete" => "boolean"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
