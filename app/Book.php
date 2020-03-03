<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public function carts(){
        return $this->hasMany(Cart::class);
    }


    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }

    public function authors(){
        return $this->hasMany(Author::class);
    }



}
