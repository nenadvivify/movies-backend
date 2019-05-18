<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
	public function comments() {
		return $this->hasMany('App\Comment');
	}
	
    public function genre() {
    	return $this->belongsTo('App\Genre');
    }
}
