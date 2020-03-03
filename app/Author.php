<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function books(){
        return $this->hasMany(Book::class);
    }
}
