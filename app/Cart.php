<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function user(){
        return $this->hasOne(User::class);
    }

    public function books(){
        return $this->hasMany(Book::class);
    }
}
