<?php


namespace App;


use Illuminate\Database\Eloquent\Builder;

trait Likable
{
    public function scopeWithLikes(Builder $query)   // type hint for Eloquent query builder
    {
        /*Initial query
        SELECT * from tweets
        left join (
                SELECT tweet_id, sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id
        ) likes on likes.tweet_id = tweets.id;*/

//    SELECT * from tweets - we already work with tweets and we can skip that
//    left join ( - we need to reproduce this with Eloquent tools
        $query->leftJoinSub(
            'SELECT tweet_id, sum(liked) likes, sum(!liked) dislikes from likes group by tweet_id',
            'likes',
            'likes.tweet_id',
            'tweets.id'
        );
    }

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
