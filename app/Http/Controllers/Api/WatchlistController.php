<?php

namespace App\Http\Controllers\Api;

use App\Movie;
use App\Watchlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WatchlistController extends Controller
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
        return auth()->user()->watchlist;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        auth()->user()->watchlist()->syncWithoutDetaching($id);
        return $this->index();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        auth()->user()->watchlist()->updateExistingPivot($id, ['watched' => true]);
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->user()->watchlist()->detach($id);
        return $this->index();
    }
}
