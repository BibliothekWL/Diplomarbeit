<?php

namespace App\Http\Controllers;

use App\Author as Author;
use App\Book as Book;
use Illuminate\Http\Request;

use App\Cart as Cart;

class AuthorController extends Controller
{
    public function create()
    {
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);

        if (Author::where('surname', $jsonarray['surname'])
                ->where('firstname', $jsonarray['firstname'])
                ->first()->count() > 0) {
            return json_encode(['status' => 400, 'statusMessage' => 'Author already exists']);
        } else {
            $author = new Author();
            $author->firstname = $jsonarray['firstname'];
            $author->surname = $jsonarray['surname'];
            $author->save();
        }

    }

    public function destroy(){
    }

}
