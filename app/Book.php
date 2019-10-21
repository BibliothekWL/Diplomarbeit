<?php

namespace App;
header("Access-Control-Allow-Origin: *");

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'title', 'systematik', 'medium', 'content', 'BNR', 'author_id'
    ];

}
