[TOC]

## Tweeter Clone Project

## 1 Setup

Create new laravel project

```bash
laravel help new
Description:
  Create a new Laravel application.

Usage:
  new [options] [--] [<name>]

Arguments:
  name                  

Options:
      --dev             Installs the latest "development" release
      --force           Forces install even if the directory already exists
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

```bash
laravel new
```

Now we need to create authentication scaffolding

```bash
composer require laravel/ui --dev
```

```bash
php artisan ui vue --auth
```

Then we need to configure our database and migrate

```bash
root@001fc1e3415b:/var/www/tweety# php artisan migrate
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (1.34 seconds)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (1.11 seconds)
Migrating: 2019_08_19_000000_create_failed_jobs_table
Migrated:  2019_08_19_000000_create_failed_jobs_table (0.61 seconds)
```

Let's add little bit of style with tailwind library for that https://tailwindcss.com/docs/installation

```bash
root@001fc1e3415b:/var/www/tweety# npm install tailwindcss
```

If you're writing your project in plain CSS, use Mix's `postCss` method to process your CSS and include `tailwindcss` as a plugin in webpack.mix.js 

```js
mix.js('resources/js/app.js', 'public/js'); // compile js

mix.postCss('resources/css/main.css', 'public/css', [ // compile css
    require('tailwindcss'),
])
```

We add in resources/view new directory css and file main.css and add tailwind 

```css
@tailwind base;

@tailwind components;

@tailwind utilities;
```

Install dependencies and run dev to compile everything down

```bash
root@001fc1e3415b:/var/www/tweety# npm install
```

```bash
root@001fc1e3415b:/var/www/tweety# npm run dev
```

Now we obtain main.css file in public/css. Next we need to import it in resources/views/layouts/app.blade.php string 20

```html
<link href="{{ asset('css/main.css') }}" rel="stylesheet">
```

 Next in resources/views/welcome.blade.php in 83-85 strings

```html
                <div class="title m-b-md">
                    Tweety
                </div>
```

Remove links 88-95

```html
                <div class="links">
                </div>
```

Grab routing 68-80 and paste in 75

```html
<div class="links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
```

## 2 Design the Timeline

Remove closely everything from app.blade.php layout

```html
<div id="app">
        <section class="px-8 py-4">
        <header class="container mx-auto">
            <h1>
                <img src="/images/logo.png"
                     alt="Tweety">
            </h1>
        </header>
        </section>

        <section class="px-8">
        <main class="container mx-auto">
            @yield('content')
        </main>
        </section>
    </div>
```

Design home.blade.php. First we create three blocks of information

```html
@extends('layouts.app')

@section('content')
    <div class="flex">
        <div class="flex-1">1</div>
        <div class="flex-1">2</div>
        <div class="flex-1">3</div>
    </div>
@endsection
```

In 1st block we include sidebar links. We need to create new view file `_sidebar-links.blade.php` (_ is a convention )

```html
<ul>
    <li><a
            class="font-bold text-lg mb-4 block"
            href="/"
        >Home</a></li>
    <li><a
            class="font-bold text-lg mb-4 block"
            href="/explore"
        >Explore</a></li>
    <li><a
            class="font-bold text-lg mb-4 block"
            href="#"
        >Notifications</a></li>
    <li><a
            class="font-bold text-lg mb-4 block"
            href="#"
        >Messages</a></li>
        ...			
```

In 3rd block we include friends list.  We need to create new view file `_friends-list.blade.php` (_ is a convention )

```html
<h3 class="font-bold text-xl mb-4">Friends</h3>
<ul>
    @foreach(range(1,8) as $index)
    <li class="mb-4">
        <div class="flex items-center text-sm">
            <img
                src="https://i.pravatar.cc/40"
                alt=""
                class="rounded-full mr-2"
            >
            John Doe
        </div>
    <li>
    @endforeach
</ul>
```

Next we need to create tweak box and timeline. We create _publish-tweet-panel.blade.php

```html
<div class="border border-blue-400 rounded-lg p-8">
    <form action="">
                    <textarea
                        name="body"
                        class="w-full"
                        placeholder="What's up doc?"
                    ></textarea>
        <hr class="my-4">
        <footer class="flex justify-between">
            <img
                src="https://i.pravatar.cc/40"
                alt=""
                class="rounded-full mr-2"
            >
            <button type="submit" class="bg-blue-500 rounded-lg shadow py-2 px-2 text-white">Tweet-a-roo!
            </button>
        </footer>
    </form>
</div>
```

We create single tweet template _tweet.blade.php\

```html
<div class="flex p-4">
    <div class="mr-2 flex-shrink-0">
        <img
            src="https://i.pravatar.cc/50"
            alt=""
            class="rounded-full mr-2"
        >
    </div>
    <div>
        <h5 class="font-bold mb-4">John Doe</h5>
        <p class="text-sm">Laravel is a web application framework with expressive, elegant syntax.
            We believe development must be an enjoyable, creative experience to be truly fulfilling.
            Laravel attempts to take the pain out of development by easing common tasks used in the
            majority of web projects, such as authentication, routing, sessions, and caching.
        </p>
    </div>
</div>

```

And finally we define home.blade.php 

```html
@extends('layouts.app')

@section('content')
    <div class="lg:flex lg:justify-between">
        <div class="lg:w-32">
            @include('_sidebar-links')
        </div>
        <div class="lg:flex-1 lg:mx-10" style="max-width: 700px">
            @include('_publish-tweet-panel')
            <div class="border border-grey-300 rounded-lg">
                @include('_tweet')
                @include('_tweet')
                @include('_tweet')
                @include('_tweet')
            </div>
        </div>
        <div class="lg:w-1/6 bg-blue-100 rounded-lg p-4">
            @include('_friends-list')
        </div>
    </div>
@endsection
```

## 3 Make the Timeline Dynamic

Let's start with the core of our application - tweet - create model with factory, migration and controller

```bash
root@001fc1e3415b:/var/www/tweety# php artisan make:model Tweet -fmc
Model created successfully.
Factory created successfully.
Created Migration: 2020_09_09_071841_create_tweets_table
Controller created successfully.
```

Next step is to define our tweet migration

```php
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            //  tweet belongs to a User
            //  foreignId do the same as biIncrement
            $table->foreignId('user_id');
            $table->string('body');
            $table->timestamps();
        });
    }
```

Migrate

```php
root@001fc1e3415b:/var/www/tweety# php artisan migrate
Migrating: 2020_09_09_071841_create_tweets_table
Migrated:  2020_09_09_071841_create_tweets_table (2.56 seconds)
```

Now we need to define Tweet Factory

```php
$factory->define(Tweet::class, function (Faker $faker) {
    return [
        //  Laravel will create new user and persist it if it necessary
        'user_id' => factory(App\User::class),
        'body' => $faker->sentence
    ];
});
```

And add new tweet in tinker

```bash
>>> factory('App\Tweet')->create();
=> App\Tweet {#3209
     user_id: 5,
     body: "Blanditiis voluptas quibusdam quas non.",
     updated_at: "2020-09-09 07:32:59",
     created_at: "2020-09-09 07:32:59",
     id: 1,
   }
```

Tweak user_id to 1. Now we need to re-define our view to be dynamic. home view:

```html
<div class="border border-grey-300 rounded-lg">
                @foreach($tweets as $tweet)
                    @include('_tweet')
                @endforeach
            </div>
```

tweet view:

```html
<div>
        <h5 class="font-bold mb-4">{{ $tweet->user->name }}</h5>
        <p class="text-sm">
            {{ $tweet->body }}
        </p>
    </div>
```

Now we need to define our variables in HomeController

```php
/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // load the home view and fetch all for now
        return view('home', [
            'tweets' => Tweet::all()
        ]);
    }
```

To make it work we need to setup relationship between tweet and user in Tweet model

```php
public function user() {
        return $this->belongsTo(User::class);
    }
```

To make an avatar belong to specific user we define it on the view

```html
<div class="mr-2 flex-shrink-0">
        <img
            src="https://i.pravatar.cc/50?u={{ $tweet->user->email }}"
            alt=""
            class="rounded-full mr-2"
        >
```

Now we can revise our index method in HomeController to return only logged user tweets in descending order.

```php
public function index()
    {
        // load the home view and fetch user timeline
        return view('home', [

            'tweets' => auth()->user()->timeline()
        ]);
    }
```

Method timeline need to be defined in User model with initial constraints 

```php
    /**
     * @return mixed
     */
    public function timeline () {
        //  fetch latest tweet
        return Tweet::where('user_id', $this->id)->latest()->get();
    }
}
```

Now we hardcode user avatar in publish-tweet-panel

```html
src="https://i.pravatar.cc/40?u={{auth()->user()->email}}"
```

but it is better practice to extract this to a partial in User model by adding getter

```php
/**
     * @return string
     */
    public function getAvatarAttribute() //custom accessor
    {
        return "https://i.pravatar.cc/40?u=" . $this->email;
    }
```

Now we recreate our view for tweet

```html
<div class="flex p-4 border-b border-b-grey-400">
    <div class="mr-2 flex-shrink-0">
        <img
            src="{{ $tweet->user->getAvatarAttribute() }}"
            alt=""
            class="rounded-full mr-2"
        >
    </div>
    <div>
        <h5 class="font-bold mb-4">{{ $tweet->user->name }}</h5>
        <p class="text-sm">
            {{ $tweet->body }}
        </p>
    </div>
</div>
```

and for publish-panel where we define also http method and action 

```html
<div class="border border-blue-400 rounded-lg p-8  mb-8">
    <form action="POST" action="/tweets">
        @csrf
                    <textarea
                        name="body"
                        class="w-full"
                        placeholder="What's up doc?"
                        required
                    ></textarea>
        <hr class="my-4">
        <footer class="flex justify-between">
            <img
                src="{{auth()->user()->getAvatarAttribute()}}"
                alt=""
                class="rounded-full mr-2"
            >
            <button
                type="submit"
                class="bg-blue-500 rounded-lg shadow py-2 px-2 text-white"
            >Tweet-a-roo!
            </button>
        </footer>
    </form>
</div>
```

Now we need to create endpoint for /tweets

```php
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/tweets', 'TweetController@store');
```

And define method store

```php
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
        return redirect('/home');
    }
```

Also in Tweet model we need to define fillable array

```php
protected $guarded = [];
```

## 4 Build a Following

Now we need to define following list instead of friends on the view

```html
<h3 class="font-bold text-xl mb-4">Following</h3>
<ul>
    @foreach(auth()->user()->follows as $user)
    <li class="mb-4">
        <div class="flex items-center text-sm">
            <img
                src={{ $user->avatar }}
                alt=""
                class="rounded-full mr-2"
            >
            {{ $user->name }}
        </div>
    <li>
    @endforeach
</ul>

```

Then we create migration for our manyToMany relationship

```php
public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->primary(['user_id', 'following_user_id']);
            $table->foreignId('user_id');
            $table->foreignId('following_user_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('following_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
```

Next we define this relationship in User model

```php
public function follows()
    {
        //  we provide explicit name of table - not following the convention user_user
        //  also because we don't use custom column names we need to define foreign to the key
        //  and related to the key
        return $this->belongsToMany(User::class,
            'follows',
            'user_id',
            'following_user_id');
    }
```

Now we need to define method follow() in User model to create this relationship

```php
public function follow(User $user)
    {
        return $this->follows()->save($user);
    }
```

## 5 Expanding Timeline

Note that our home controller actually displaying timeline but it should be tweets controller that displays timeline. So we add it

```php
Route::get('/tweets', 'TweetController@index');
```

So we detach index method from home controller and inject it into tweet controller and delete home controller and name our new route

```php
Route::get('/tweets', 'TweetController@index')->name('home');;
```

In our login controller by default laravel will redirect to this string

```php
protected $redirectTo = RouteServiceProvider::HOME;
```

And if we look at it - it is actually hardcoded path - we update it

```php
public const HOME = '/tweets';
```

Now we no longer have any protection, so even if i am guest it tries to load the page and failed with exposing error. Now we can implement two approaches: grouping in routes or add your authentication middleware in controller

```php
//  apply auth middleware to this group
Route::middleware('auth')->group(function (){
    Route::post('/tweets', 'TweetController@store');
    Route::get('/tweets', 'TweetController@index')->name('home');
});
```

So now we need to update timeline

```php
public function timeline()
    {
        //  fetch latest tweet
        return Tweet::where('user_id', $this->id)
            ->latest()
            ->get();
    }
```

We add new relationship

```php
public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }
```

And redefine our timeline()

```php
public function timeline()
    {
        // include all of the user's tweets
        //  as well as the tweets of everyone
        //  they follow..in descending order
        //  grab ids of followers and push to collection
        //  id of itself
        $ids = $this->follows->pluck('id');
        $ids->push($this->id);
        //  give me tweets from collection
        return Tweet::whereIn('user_id', $ids)->latest()->get();
    }
```

Also we can refactor it for more efficient performance and expressiveness

```php
public function timeline()
    {
        $friends = $this->follows()->pluck('id');
        
        return Tweet::whereIn('user_id', $friends)
            ->orWhere('user_id', $this->id)
            ->latest()->get();
    }
```

```
$friends = $this->follows()->pluck('id');
```

^ Find all the users that the current person follows. But we don't want the full `User` model; we only care about the user's id. So we'll "pluck" the `id` column.

```
return Tweet::whereIn('user_id', $friends)
```

^ Give me only the tweets where the `user_id` (the person who created the tweet) is in the list of `$friends` that we fetched earlier. Remember, we don't want all tweets in the database. We only care about the tweets from our friends.

```
->orWhere('user_id', $this->id)
```

^ We also want to see our own tweets, so let's add that to the query as well.

```
->latest()
```

^ Order the results in descending order according to the `created_at` timestamp. This means the most recent tweets show up first, which makes sense.

```
->get()
```

^ My query is ready to go, so let's execute and "get" the results.

## 6 Construct the Profile Page

First we change the name of the view in Tweet controller and replace and rename  view

```php
public function index()
    {
        // load the home view and fetch user timeline
        return view('tweets.index', [

            'tweets' => auth()->user()->timeline()
        ]);
    }
```

We need to refactor our friend list to not get a trap with undefined auth property follows

```php+HTML
@auth
<h3 class="font-bold text-xl mb-4">Following</h3>
<ul>
    @foreach(auth()->user()->follows as $user)
    <li class="mb-4">
        <div class="flex items-center text-sm">
            <img
                src={{ $user->avatar }}
                alt=""
                class="rounded-full mr-2"
            >
            {{ $user->name }}
        </div>
    <li>
    @endforeach
</ul>
@endauth
```

After ending housekeeping we need to create route to view our profile

```php
Route::get('/profiles/{user}', 'ProfilesController@show');
```

Create new controller and define method with type hinting.

```php
public function show(User $user)
    {
        return $user;
    }
```

 NOTE that by default that route key name  is id of the user. How we can define it. First let's make a little snippet to spit out SQL query in routes

```php
DB::listen(function ($query) {var_dump($query->sql, $query->bindings);});
```

And we get sql-query

```
select * from `users` where `id` = "JonDoe"
```

If we override method getRouteKeyName() in user model to point name key (laravel 6 and below) - key to use is the user's name

```php
public function getRouteKeyName()
    {
        return 'name';
    }
```

```
select * from `users` where `name` = "JonDoe"
```

Now we tell show method to return specific view and with template engine create it

```php
  public function show(User $user)
    {
        return view('profiles.show', compact('user'));
    }
```

Now we create link - avatar and user name should link here. First we need to give it a name in routes

```php
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
```

On the view we define route name and key is user name

```php+HTML
<a href="{{ route('profile', $tweet->user) }}">
        <img
            src="{{ $tweet->user->getAvatarAttribute() }}"
            alt=""
            class="rounded-full mr-2"
        >
        </a>
```

We provide full user model and we expect provide attribute itself but Laravel is smart enough to know that we ask for name.

Now we can provide links for friend list, home page and auth-user profile page

```php+HTML
<a href="{{ route('profile', $user) }}" class="flex items-center text-sm">
            <img
                src={{ $user->avatar }}
                alt=""
                class="rounded-full mr-2"
            >
            {{ $user->name }}
            </a>
```

```php+HTML
<li><a
            class="font-bold text-lg mb-4 block"
            href="{{ route('home') }}"
        >Home</a></li>
```

```php+HTML
<li><a
            class="font-bold text-lg mb-4 block"
            href="{{route('profile', auth()->user())}}"
        >Profile</a></li>
```

Let's extract timeline to separate view, and before we can add it as a template on our profile page we need to define what tweets do we need for timeline - we could say like this we add relationship in User model

```php+HTML
@include('_timeline',[
    'tweets' => $user->tweets
    ])
```

And do some view stuff for profile page 

```php+HTML
@extends('layouts.app')

@section('content')
    <header class="mb-6 relative">
        <img class="mb-2"
        src="/images/default-profile-banner.jpeg"
        alt=""
        >
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl mb-0">{{ $user->name }}</h2>
                <p class="text-sm">Joined {{ $user->created_at->diffForHumans() }}</p>
            </div>

            <div>
                <a class="rounded-full border border-gray-300 py-4 px-2 text-black text-l mr-2">Edit Profile</a>
                <a class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs">Follow Me</a>
            </div>
        </div>
        <p class="text-small mt-2">
            Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation —
            freeing you to create without sweating the small things.
        </p>
        <img
            src="{{ $user->getAvatarAttribute() }}"
            alt=""
            class="rounded-full mr-2 absolute"
            style="width: 150px; left: calc(50% - 75px); top: 140px"
        >

    </header>

    @include('_timeline',[
    'tweets' => $user->tweets
    ])
@endsection
```

## 7 Nested Layout Files with Components

When we visit when we not signed in it redirects us to login or register page and we obtain error `Missing required parameters for [Route: profile] [URI: profiles/{user}]. (View: /var/www/tweety/resources/views/_sidebar-links.blade.php)` 

The layout file assumes that we are signed in. We have some options

First is to check for authentication

```php+HTML
 @if (auth()->check())
     <div class="lg:w-32">
     @include('_sidebar-links')
     </div>
@endif
```

Second is to add new master layout component

```php+HTML
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
...
</head>
<body>
<div id="app">
    <section class="px-8 py-4 mb-6">
        <header class="container mx-auto">
            <h1>
                <img src="/images/logo.png"
                     alt="Tweety">
            </h1>
        </header>
    </section>
    {{ $slot }} // for <x-master></x-master>
</div>
</body>
</html>
```

Transfer app.blade.php into components and use component tag `<x-master></x-master>` and input new slots

```html
<x-master>
    <section class="px-8">
        <main class="container mx-auto">
            <div class="lg:flex lg:justify-between">
                @if (auth()->check())
                    <div class="lg:w-32">
                        @include('_sidebar-links')
                    </div>
                @endif
                <div class="lg:flex-1 lg:mx-10" style="max-width: 700px">
                    {{ $slot }}
                </div>
                @if (auth()->check())
                    <div class="lg:w-1/6 bg-blue-100 rounded-lg p-4">
                        @include('_friends-list')
                    </div>
                @endif
            </div>
        </main>
    </section>
</x-master>
```

In this manner we nest register and login pages. The when we need to access our home page (tweets/index.blade.php) we need to nest it into <x-app>

```php
<x-app>
    <div>
    @include('_publish-tweet-panel')
    @include('_timeline')
    </div>
</x-app>

```

Useful and alternative ways that we can allow multiple layout files - nested layout files

## 8 Build the Follow Form

Now we need to add new functionality to User model for checking who User is following. **Whenever we have roughly three or more relative methods on a model it is a good practice to extract them as a trait**. Let's add it in our model

```php
class User extends Authenticatable
{
    use Notifiable, Followable;
```

Create trait in app directory and pull out from User model this methods

```php
trait Followable
{

    public function following()
    {

    }

    public function follows()
    {
        //  we provide explicit name of table - not following the convention user_user
        //  also because we don't use custom column names we need to define foreign to the key
        //  and related to the key
        return $this->belongsToMany(User::class,
            'follows',
            'user_id',
            'following_user_id');
    }

    public function follow(User $user)
    {
        return $this->follows()->save($user);
    }
}
```

Now we can refactor our key of route, by removing it from (for laravel 7 and above) User model and define attribute in the wildcard

```php
Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');
```

In this way we clean up our user model - it is very important because **for any application User model is the first thing that becomes caught object**.

Now let's implement follow.

```html
<form method="post" action="/profiles/{{ $user->name }}/follow">
                    @csrf

                    <button
                        type="submit"
                        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
                    >
                        Follow Me
                    </button>
                </form>
```

Now we need to define this new endpoint in web routes

```php
Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');
```

This leads us to create new controller with store() method

```PHP
public function store(User $user)
    {
        //  have the authenticated user follow the given user
        auth()
            ->user()
            ->follow($user);

        return back();
    }
```

We need to provide some feedback. Let's do check if the current user is following this user. If does it shows Unfollow, if not it shows Follow

```php
{{auth()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
```

Now let's return to following method in Followable trait with first approach

```php
public function following(User $user)
    {
        //  get a collection of every one that current user follows and check if it contains a given user
        return $this->follows->contains($user);

    }
```

Downside here is that we fetch a collection. Always be careful because in this way we load entire collection. Instead we can do a sql query

```php
return $this->follows()
            ->where('following_user_id', $user->id)
            ->exists();
```

We don't have yet functionality to Unfollow. Depending of how we architecture application we have some ways to do this.

1. Single form and single endpoint - you choose that

2. Two different forms (more RESTful way) 

   ```php
   @if (auth()->user()->following($user))
   ```

   Two different endpoint one submits post request to follow another submits delete request to Unfollow.
   
3. View component

4. Livewire component

We toggle it In the FollowsController but before it we need to define unfollow() method and toggleFollow() in Followable trait

```php
/**
     * @param User $user
     * @return mixed
     */
    public function unfollow(User $user)
    {
        return $this->follows()->detach($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function toggleFollow(User $user)
    {
        if ($this->following($user)) {
            return $this->unfollow($user);
        }
        return $this->follow($user);
    }
```

And redefine store method in controller with that new method

```php
public function store(User $user)
    {
        //  have the authenticated user follow the given user
        auth()->user()->toggleFollow($user);

        return back();
    }
```

As a next step we can extract Follow\Unfollow button into component, **but we need to pass user object - anonymous blade component**

```html
<x-follow-button :user="$user"></x-follow-button>
```

```html
<form method="post" action="/profiles/{{ $user->name }}/follow">
    @csrf

    <button
        type="submit"
        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
    >
        {{auth()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
    </button>
</form>
```

We add path method in User model, so we define single place where we declare it and do refactoring of the view.

```php
public function path()
{
    return route('profile', $this->name);
}
```

Also we need to redefine our redirection in TweetController store() method. because we already obtain named routes

```php
return redirect()->route('home');
```

And in the order to see in profile latest tweet first we redefine our tweets() method in User model

```php
public function tweets()
{
    return $this->hasMany(Tweet::class)
        ->latest();
}
```

## 9. Profile Authorization Logic

Define condition on which we show follow\unfollow button in the view with blade syntax

```php+HTML
@unless(current_user()->is($user))
<form method="post" action="/profiles/{{ $user->name }}/follow">
    @csrf

    <button
        type="submit"
        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
    >
        {{auth()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
    </button>
</form>
@endunless
```

Define condition on which we show edit/profile button in the view with blade syntax 

```php+HTML
@if(current_user()->is($user))
                <a
                    href=""
                    class="rounded-full border border-gray-300 py-4 px-2 text-black text-l mr-2"
                >
                    Edit Profile
                </a>
                @endif
```

In situation when we want to add global helper function. Create app/helpers.php and create helper function 

```php
function current_user()
{
    return auth()->user();
}
```

Add it to composer.json in autoload:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/"
    },
    "classmap": [
        "database/seeds",
        "database/factories"
    ],
    "files": [
        "app/helpers.php"
    ]
},
```

And do `composer dump-autoload`.

Now we add functionality for edit button by adding link

```php+HTML
href="{{ $user->path('edit') }}"
```

Now we need to revise our path() method in User model

```php
/**
 * @param string $append
 * @return string
 */
public function path($append = '')
{
    $path =  route('profile', $this->name);
    return $append ? "{$path}/{$append}" : $path;
}
```

Next we create new route in middleware auth group (laravel 7 and below)

```php
Route::get('/profiles/{user:name}/edit', 'ProfilesController@edit');
```

Define new method

```php
protected function edit(User $user)
{
    return view('profiles.edit', compact('user'));
}
```

And create new view profiles/edit.blade.php. Now we need a restriction for editing not authorized profiles that we obtain if we manually access.

First option is to define it explicitly in controller and return 404 

```php
abort_if($user->isNot(current_user()), 404);
```

Second and more good practice is to create new policy and define new rule for edit()

```php
public function edit(User $currentUser, User $user)
{
    return $currentUser->is($user);
}
```

Next we have two approaches to catch it:

And we catch this exception with laravel tools in model

```php
$this->authorize('edit', $user);
```

Or we can define it in route

```php
Route::get(
    '/profiles/{user:name}/edit',
    'ProfilesController@edit'
)->middleware('can:edit,user'); // can we edit this wildcard named 'name'
```

In addition when we create policy we can get rid-off helper function in the view for edit button

```php
@can('edit', $user)
/*...*/
@endcan
```

## 10. File Storage and Custom Avatars

Let's revise our user migration

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username')->unique();
    $table->string('name');
    $table->text('avatar')->nullable();
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

And recreate all tables in database `php artisan migrate:fresh`

No we add new field for username in registration form

```php+HTML
<div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">Username</label>

    <div class="col-md-6">
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

        @error('username')
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
```

Next step is to update RegisterController validate and create fields

```php
return Validator::make($data, [
    'username' => [
        'required',
        'string',
        'max:255',
        'unique:users', //  check for duplication in users table
        'alpha_dash'    //  validation rule that exclude spaces
    ],
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'string', 'min:8', 'confirmed'],
]);
```

```
return User::create([
    'username' => $data['username'],
    'name' => $data['name'],
    'email' => $data['email'],
    'password' => Hash::make($data['password']),
]);
```

In User model fillable fields add attributes that could be mass-assigned

```php
protected $fillable = [
    'username', 'name', 'email', 'password',
];
```

Update method path()

```php
$path =  route('profile', $this->username);
```

The update all routes to refer to username as a key, not  a name

```php
Route::middleware('auth')->group(function (){
    Route::post('/tweets', 'TweetController@store');
    Route::get('/tweets', 'TweetController@index')->name('home');

    Route::post('/profiles/{user:username}/follow', 'FollowsController@store');
    Route::get(
        '/profiles/{user:username}/edit',
        'ProfilesController@edit'
    )->middleware('can:edit,user'); // can we edit this wildcard named 'username'
});

Route::get('/profiles/{user:username}', 'ProfilesController@show')->name('profile');
```

Now we define our edit form with PATCH request https://tools.ietf.org/html/rfc5789 - partial resource modification.

```php+HTML
<x-app>
    <form method="POST" action="{{ $user->path() }}">
        @csrf
        {{--Submit PATCH request to users endpoint rfc5789--}}
        @method('PATCH')
        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="name">Name</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="text"
                   name="name"
                   id="name"
                   value="{{ $user->name }}"
                   required
                   >
            @error('name')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="username">Username</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="text"
                   name="username"
                   id="username"
                   value="{{ $user->username }}"
                   required
            >
            @error('username')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="email">Email</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="email"
                   name="email"
                   id="email"
                   value="{{ $user->email }}"
                   required
            >
            @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="password">Password</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="password"
                   name="password"
            >
            @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                   for="password_confirmation">Password Confirmation</label>
            <input class="border border-gray-400 p-2 w-full"
                   type="password"
                   name="password_confirmation"
                   id="password_confirmation"
            >
            @error('password_confirmation')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <button type="submit"
                    class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                Submit
            </button>
        </div>
    </form>
</x-app>
```

Now we need to create endpoint

```php
Route::patch('/profiles/{user:username}', 'ProfilesController@update');
```

Create new method update in ProfilesController

```php
public function update(User $user)
{
    $attributes = request()->validate([
        'username' => [
            'string',
            'required',
            'max:255',
            Rule::unique('users')->ignore($user),
            'alpha_dash'
        ],
        'name' => [
            'required',
            'string',
            'max:255'
        ],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user)
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed'],
    ]);

    $user->update($attributes);

    return redirect($user->path());
}
```

Now we need to add avatar update to our form

```php+HTML
<div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="avatar">Avatar</label>
    <input class="border border-gray-400 p-2 w-full"
           type="file"
           name="avatar"
           id="avatar"
           required
    >
    @error('avatar')
    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>
```

After we submit we get an UploadedFile instance. Add this attribute to validation

```php
'avatar' => [
    'required',
    'file'
],
```

And store in database the path for stored avatar in avatars/

```php
$attributes['avatar'] = request('avatar')->store('avatars');
```

And make it fillable in model

```php
protected $fillable = [
    'username', 'avatar', 'name', 'email', 'password',
];
```

In config/filesystems.php we can define storage system for files. To choose what we need set it in .env

```bash
FILESYSTEM_DRIVER=public
```

Next step is to create simlink from storage/public to actual public directory

```bash
php artisan storage:link
The [/var/www/tweety/public/storage] link has been connected to [/var/www/tweety/storage/app/public].
The links have been created.
```

Now we end up with revising our method in  User model to access proper place

```php
public function getAvatarAttribute($value) //custom accessor
{
    return asset('/storage/'.$value);
}
```

## 11. Build the Explore Users Page

Our password is not hashed. We can add custom mutator in model

```php
//  $user->password = 'foobar'
//  it will first be piped through this method
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = bcrypt($value);
}
```

Next we need to make avatar optional in ProfileController update method

```php
'avatar' => [
    'file'
],
```

And store it only if it present

```php
if (request('avatar')) {
    $attributes['avatar'] = request('avatar')->store('avatars');
}
```

Next let's revise our toggleFollow method with cleaner code

```php
public function toggleFollow(User $user)
{
    $this->follows()->toggle($user);
}
```

Next notice that if we want to edit an existing Profile we added a necessary middleware 

```php
Route::patch(
    '/profiles/{user:username}',
    'ProfilesController@update'
)->middleware('can:edit,user');
```

Next we need to implement explore page.

1. Set Route in auth group

   ```php
   Route::get('/explore', 'ExploreController@index');
   ```

2. Create controller and method 

   ```php
   class ExploreController extends Controller
   {
       public function index()
       {
           return view('explore');
       }
   }
   ```

3. Create view explore.blade.php

4. In explore controller pass through to view users instances 

   ```php
   public function index()
   {
       return view('explore', [
           'users' => User::paginate(50),
       ]);
   }
   ```

5. Define view 

   ```php
   <x-app>
       <div>
           @foreach($users as $user)
               <a href="{{ $user->path() }}" class="flex items-center mb-5">
                   <img
                       src="{{ $user->avatar }}"
                       alt="{{ $user->username }}'s avatar"
                       width="60"
                       height="60"
                       class="mr-4 rounded"
                   >
                   <div>
                       <h4 class="font-bold"> {{ '@' . $user->name }}</h4>
                   </div>
               </a>
           @endforeach
       </div>
   </x-app>
   ```

6. Update follow-button 

   ```php+HTML
   <form method="POST"
         action="{{ route('follow', $user->username) }}">
   ```

7. Update routes

   ```php
   Route::post(
       '/profiles/{user:username}/follow',
       'FollowsController@store'
   )->name('follow');
   ```

   

## 12. Clean Up

1. Login form and register form revised. 

   ```php+HTML
   <div class="container mx-auto flex justify-center">    <div class="px-12 py-8 bg-gray-200 border border-gray-300 rounded-lg">        <div class="col-md-8">            <div class="font-bold text-xl mb-4 py-4">{{ __('Login') }}                <div class="ard-body py-2">                    <form method="POST" action="{{ route('login') }}">                        @csrf                        <div class="mb-6">                            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"                                   for="email"                            >                                Email                            </label>​                            <input class="border border-gray-400 p-2 w-full"                                   type="text"                                   name="email"                                   id="email"                                   autocomplete="email"                                   value="{{ old('email') }}"                            >​                            @error('email')                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>                            @enderror                        </div>                        .....
   ```

2. _friends.list 

   ```php+HTML
   div class="px-12 py-4 bg-gray-200 border border-gray-300 rounded-lg">
       <h3 class="font-bold text-xl mb-4">Following</h3>
           <ul>
               @forelse(auth()->user()->follows as $user)
                   <li class="{{ $loop->last ? '' : 'mb-4'}}">
   ```

3. _publish-tweet 

   ```php+HTML
   @csrf
   <textarea
   	name="body"
   	class="w-full"
   	placeholder="What's up doc?"
   	required
       autofocus
                   ...
   <footer class="flex justify-between items-center">
   ...
   <button
   	type="submit"
   	class="bg-blue-400 shadow px-10 h-10 text-white text-sm rounded-lg hover:bg-blue-500"
   	>Tweet-a-roo!
   </button>
   ```

4. The explore controller is descend candidate for invokable controller. **Invocable controller** - controller that have single action. We can create it with magic method The [__invoke()](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke) method is called when a script tries to    call an object as a function.   

   ```php
   Route::get('/explore', 'ExploreController');
   ```

   ```php
   public function __invoke()
       {
           return view('explore', [
               'users' => User::paginate(50),
           ]);
       }
   ```

5. _timeline pagination 

   ```php+HTML
   @include('_timeline',[
       'tweets' => tweets
       ])
   ```

   In ProfilesController we revise method show() 

   ```php
   public function show(User $user)
   {
       return view('profiles.show', [
           'user' => $user,
           'tweets' => $user->tweets()->paginate(3)
       ]);
   }
   ```

   In _timeline add links and publish pagination - it allows us to be in control of that

   ```php+HTML
       {{ $tweets->links() }}
   ```

   in bootstrap-4 

   ```php+HTML
   @if ($paginator->hasPages())
       <nav>
           <ul class="flex justify-between w-64 p-4 mx-auto">
               ...
               @if ($page == $paginator->currentPage())
                               <li class="page-item active text-blue-400 hover:text-blue-500"
                                   aria-current="page"
                               >
                                   <span class="page-link">{{ $page }}</span></li>
                           @else
   ```

   in User model 

   ```php
   public function timeline()
   {
       $friends = $this->follows()->pluck('id');
   
       return Tweet::whereIn('user_id', $friends)
           ->orWhere('user_id', $this->id)
           ->latest()
           ->paginate(50);
   }
   ```

6. _sidebar links logout button 

   ```php+HTML
   <li>
       <form method="POST" action="/logout">
           @csrf
       <button class="font-bold text-lg">Logout</button>
       </form>
   </li>
   ```

7. edit profile cancel link 

   ```php+HTML
   <a href="{{ $user->path() }}" class="hover: underline">Cancel</a>
   ```

## 13. Build a Like/Dislike System

### 13.1 Likes Table Migration

1. ```bash
   root@43c4b04a340f:/var/www/tweety# php artisan make:migration create_likes_table
   ```

   ```php
   public function up()
       {
           Schema::create('likes', function (Blueprint $table) {
               $table->id();
               //  laravel 7+ approach to setup link and cascade
               $table->foreignId('user_id')
                   ->constrained()
                   ->onDelete('cascade');
               $table->foreignId('tweet_id')
                   ->constrained()
                   ->onDelete('cascade');;
                   
               $table->boolean('liked'); // 0 disliked
               $table->timestamps();
               //  traditional way to setup link
   //            $table->foreign('user_id')
   //                ->references('id')
   //                ->on('users')
   //                ->onDelete('cascade');
           });
       }
   ```

### 13.2 MySQL Play

Our query for likes/dislikes will look like that

```sql
SELECT tweet_id, sum(liked) likes, sum(!liked) dislikes from likes
group by tweet_id;
```

And query for all tweets will look like that

```sql
SELECT * from tweets
left join (
    SELECT tweet_id, sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id
    ) likes on likes.tweet_id = tweets.id;
```

### 13.3 Eloquent Behaviour

In Tweet model we add behavior:

1. Set up relationship in Tweet and User model 

   ```php
   public function likes()
   {
       return $this->hasMany(Like::class);
   }
   ```

2. Create Like model and extend Eloquent model abstract class

3. In Tweet model setup like() and dislike() methods. First we need to go back to migration and add unique constraint: 

   ```php
   			//  unique constraint on index
               //  we protected from situation when we have both like and dislike
               $table->unique(['user_id', 'tweet_id']);
   ```

   In methods like instead of create() we will utilize method updateOrCreate() and accept $liked parameter as default true. With that approach we can simplify dislike method to call like method with constant false parameter. Also for cleaner API  for fetching user_id we can accept $user as a parameter but not requiring it.

   ```php
   public function like($user = null, $liked = true)
       {
           // Create Like model instance
           $this->likes()->updateOrCreate(
               [
                   'user_id' => $user ? $user->id : auth()->id(),
               ],
               [
                   'liked' => $liked
               ]);
       }
   
       public function dislike($user = null)
       {
           return $this->like($user, false);
       }
   ```

   Now we add methods for check for likes and dislikes 

   ```php
   public function isLikedBy(User $user)
       {
           //  we can go through all of the likes and check for presence
           //  but if we go through the loop it give us a n+1 problem
   //        $this->likes()->where('user_id', $user->id)->exists();
   
           //  instead we can grab likes from users, and every single tweet
           //  we are not going to run database query
           return (bool) $user->likes
               ->where('tweet_id', $this->id)
               ->where('liked', true)
               ->count();
       }
   
       public function isDislikedBy (User $user)
       {
           return (bool) $user->likes
               ->where('tweet_id', $this->id)
               ->where('liked', false)
               ->count();
       }
   ```

   And extract all relative to like system method into trait Likeable.

## Credentials

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

### About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

### Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[OP.GG](https://op.gg)**

### Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

### Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

### Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).