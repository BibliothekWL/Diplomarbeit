<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Book extends Model
{

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public static function index($array)
    {
        return new Paginator($array, 6);
    }

    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }

    protected $fillable = [
        'title', 'systematik', 'medium', 'content', 'BNR', 'author_id'
    ];

}
