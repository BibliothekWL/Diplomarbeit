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
        $author = Author::where('name', $jsonarray['name'])
            ->first();
        if ($author !== null) {
            return json_encode(['status' => 400, 'statusMessage' => 'Author already exists']);
        } else {
            $author = new Author();
            $author->name = $jsonarray['name'];
            $author->save();
            return json_encode(['status' => 200, 'statusMessage' => 'creation successful']);

        }
    }

    public function edit(){
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);

        $author = Author::findOrFail($jsonarray['id']);
        $author->name = $jsonarray['name'];
        $author->save();

        return json_encode(['status' => 200, 'statusMessage' => 'edit successful']);

    }

    public function destroy(){
        $json = file_get_contents('php://input');
        $jsonarray = json_decode($json, true);

        $author = Author::findOrFail($jsonarray['id']);
        $author->delete();

        return json_encode(['status' => 200, 'statusMessage' => 'delete successful']);
    }
}
