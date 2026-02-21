<?php

namespace App\Http\Controllers;

use App\Models\Skate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $skates = Skate::where('quantity', '>', 0)->get();
        return view('home', compact('skates'));
    }
}