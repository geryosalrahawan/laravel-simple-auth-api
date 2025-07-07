<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts;
use App\Models\User;
class comments extends Model
{
   protected $fillable = ['user_id','content'];

   public function post()
   {
       return $this->belongsTo(Posts::class);
   }
public function user()
{
    return $this->belongsTo(User::class);
}



}