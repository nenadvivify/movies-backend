<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Movie;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Movie::with('genre')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store()
    {
        request()->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        return Movie::create(request()->all());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::find($id)->load([
            'genre',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user' => function ($query) {
                $query->select(['id', 'name']);
            }
        ]);

        $movie->increment('visits', 1);
        return $movie;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function vote()
    {
        request()->validate([
            'type' => ['required', 'in:like,dislike'],
            'movie_id' => [
                'required',
                'exists:movies,id',
                Rule::notIn(request()->user()->votes)
            ],
        ], [
            'movie_id.not_in' => "You have already voted on this movie.",
        ]);

        $id = request()->input('movie_id');
        $movie = Movie::with('genre')->find($id);
        return $movie->vote();
    }

    public function similar()
    {
        request()->validate([
            'movie_id' => [
                'required',
                'exists:movies,id'
            ]
        ]);

        $id = request()->movie_id;
        $movie = Movie::with('genre')->find($id);
        return $movie->similar();
    }

    public function search()
    {
        return Movie::search(request()->search)->take(10)->get()->load('genre');
    }
}
