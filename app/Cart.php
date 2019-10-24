<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }
}
