<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    
    public function genre()
    {
        return $this->belongsTo('App\Genre');
    }

    public function vote()
    {
        $user = auth()->user();
        $vote = request()->type;

        $votes = $user->votes ?? [];
        $votes[] = $this->id;
        $user->votes = $votes;
        $user->save();

        if ($vote == 'like') {
            $this->increment('likes');
        } else {
            $this->increment('dislikes');
        }

        return $this;
    }

    public function similar()
    {
        $limit = request()->limit ?? 10;
        
        return Movie::with('genre')->whereHas('genre', function ($query) {
            $query->where('name', $this->genre->name);
        })->take($limit)->get();
    }
}
