<?php


namespace App;


trait Likable
{
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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
