<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Comment;
use App\Movie;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
    	request()->validate([
        'movie_id' => ['required', 'exists:movies,id'],
        'body' => ['required']
      ]);

    	$comment = Comment::create([
          'body' => $request->body,
          'user_id' => Auth::id(),
          'movie_id' => $request->movie_id
      ]);

    	return $comment->load('user');
    }
}
