<?php

namespace App\Http\Controllers;

use App\Cart as Cart;
use App\Book as Book;

class CartsController extends Controller
{
    public function create()
    {
        if(Book::all()->where('id',request()->bookId)->first()->borrowed == 1){
            dd('this book is not available');
        }
        elseif(Cart::all()->where('book_id',request()->bookId)->isEmpty()==false) {
            dd('this book is reserved for a user');
        }
        else{
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->book_id = request()->bookId;
            $cart->save();

            $this->index();
        }
        return(redirect('/cart'));
    }

    public function deleteAllCartsFromUser(){
        Cart::all()->where('user_id',auth()->user()->id)->delete();
    }

    public function index(){
        $carts = Cart::all()->where('user_id',auth()->user()->id);
        return view('cart.index', compact('carts'));
    }
}
