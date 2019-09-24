<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function home(){
        $tasks = 'Test';
        return view('welcome', [
            'foo' => 'bar',
            'tasks' => $tasks
        ]);
    }

    public function contact(){
        return view('contact');
    }

}
