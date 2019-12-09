<?php

namespace App\Http\Controllers;

use App\Cart as Cart;
use App\Book as Book;

class CartsController extends Controller
{
    public function create(){
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);
        if(Book::all()->where('id',$jsonarray['id'])->first()->borrowed == 1){
            return json_encode(['status' => 400, 'statusMessage' => 'Buch ist schon ausgeborgt']);
        }
        elseif(Cart::all()->where('book_id',$jsonarray['id'])->isEmpty()==false) {
            return json_encode(['status' => 400, 'statusMessage' => 'Buch ist schon reserviert']);
        }
        else{
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->book_id = $jsonarray['id'];
            $cart->save();
            $this->index();
            return json_encode(['status' => 200, 'statusMessage' => 'cart was created successfully']);
        }
    }

    public function deleteAllCartsFromUser(){
        Cart::all()->where('user_id',auth()->user()->id)->delete();
    }

    public function index(){
        $carts = Cart::all()->where('user_id',auth()->user()->id);
        return view('cart.index', compact('carts'));
    }

    public function destroy(Cart $cart){
        $cart->delete();
        return redirect('/cart');
    }
}
