<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Genre;

class GenresController extends Controller
{
    public function index() {
    	return Genre::all();
    }
}
