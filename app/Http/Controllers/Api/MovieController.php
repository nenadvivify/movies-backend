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
    public function index(Request $request)
    {
        return Movie::with('genre')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
