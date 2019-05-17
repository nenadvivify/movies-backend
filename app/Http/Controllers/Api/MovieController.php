<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Validation\Rule;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if(Auth::guard('api')->check()) {
        //     return Movie::all();
        // }

        // return response()->json([
        //     "error" => "Not authorized"
        // ], 401);

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
        // if(Auth::guard('api')->check()) {
        //     return Movie::find($id);
        // }

        // return response()->json([
        //     "error" => "Not authorized"
        // ], 401);
        $movie = Movie::find($id)->load('genre');

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

    public function vote(Request $request) {
        $request->validate([
            'type' => ['required', 'in:like,dislike'],
            'movie_id' => [
                'required', 
                'exists:movies,id', 
                Rule::notIn($request->user()->votes)
            ],
        ], [
            'movie_id.not_in' => "You have already voted on this movie.",
        ]);

        $id = $request->input('movie_id');
        $movie = Movie::with('genre')->find($id);
        $user = Auth::user();
        $vote = request()->type;

        $votes = $user->votes ?? [];
        $votes[] = $movie->id;
        $user->votes = $votes;
        $user->save();


        if ($vote == 'like') {
            $movie->increment('likes');
        } else {
            $movie->increment('dislikes');
        }

        return $movie;
    }

    public function similar(Request $request) {
        $request->validate([
            'movie_id' => [
                'required',
                'exists:movies,id'
            ]
        ]);

        $id = request()->movie_id;
        $limit = request()->limit ?? 10;

        $movie = Movie::with('genre')->find($id);
        $similar = Movie::with('genre')->whereHas('genre', function ($query) use ($movie) {
            $query->where('name', $movie->genre->name);
        })->take($limit)->get();

        return $similar;
    }
}
