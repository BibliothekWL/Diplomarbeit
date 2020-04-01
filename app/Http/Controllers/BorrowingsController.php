<?php

namespace App\Http\Controllers;

use App\Borrowing;
use Illuminate\Http\Request;

class BorrowingsController extends Controller
{
    public function index(){
        $borrowings = Borrowing::all();
        return $borrowings;
    }
}
