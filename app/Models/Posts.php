<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\comments;


class Posts extends Model
{
    /** 
    *@var list<string>
    */
protected $fillable = ['title', 'content'];
public function user()
{
    return $this->belongsTo(User::class);
}
public function comments()
{
    return $this->hasMany(comments::class);
}

}