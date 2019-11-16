<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public function carts(){
        return $this->hasMany(Cart::class);
    }

<<<<<<< HEAD
    public static function index($array)
    {
        return new Paginator($array, 6);
    }

=======
>>>>>>> f5864d5a2a3cb22953424726d24823405c2c28a8
    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }

    protected $fillable = [
        'title', 'systematik', 'medium', 'content', 'BNR', 'author_id'
    ];


}
