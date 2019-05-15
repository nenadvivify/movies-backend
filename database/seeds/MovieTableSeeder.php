<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Movie;

class MovieTableSeeder extends Seeder
{
    public function run()
    {
    	factory(App\Genre::class, 10)->create()->each(function ($genre) {
    		factory(Movie::class, 4)->create([
    			'genre_id' => $genre->id,
    		]);
    	});
       
    }
}
