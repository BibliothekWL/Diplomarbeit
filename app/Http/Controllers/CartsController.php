<?php

namespace App\Http\Controllers;

use App\Cart as Cart;
use App\Book as Book;
use Request;

class CartsController extends Controller
{
    public function create()
    {
        $cart = new Cart;
        $cart->user_id = auth()->user()->id;
        $cart->book_id = request()->bookId;
        $cart->save();

        $this->index();

        return(redirect('/cart'));
    }

    public function deleteAllCartsFromUser(){
        Cart::all()->where('user_id',auth()->user()->id)->delete();
    }

    public function index(){
        $carts = Cart::all();
        return view('cart.index', compact('carts'));
    }

}
