<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // load the home view and fetch user timeline
        return view('tweets.index', [
            'tweets' => auth()->user()->timeline()
        ]);
    }

    public function store()
    {
        //  Validate the request. Validated attributes saved as array in var
        $attributes = request()->validate(['body' => 'required|max:255']);
        //  We persist the tweet
        Tweet::create([
            'user_id' => auth()->id(),
            'body' => $attributes['body'],
            //
        ]);
        //  redirect back to home
        return redirect()->route('home');
    }

}
