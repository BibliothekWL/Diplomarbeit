<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Pagination\Paginator;

class Book extends Model
{

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }
    public static function index($array)
    {
        return new Paginator($array, 6);;
    }

    protected $fillable = [
        'title', 'systematik', 'medium', 'content', 'BNR', 'author_id'
    ];


}
