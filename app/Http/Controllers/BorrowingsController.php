<?php

namespace App\Http\Controllers;

use App\Borrowing;
use Illuminate\Http\Request;

class BorrowingsController extends Controller
{
    public function index(){
        $borrowings = Borrowing::all()->where('user_id',auth()->user()->id);
        return view('borrowing.index', compact('borrowings'));
    }
}
