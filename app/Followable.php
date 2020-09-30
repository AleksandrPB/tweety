<?php


namespace App;


trait Followable
{

    public function following(User $user)
    {
        //  get a collection of every one that current user follows and check if it contains a given user
//        return $this->follows->contains($user);
        return $this->follows()
            ->where('following_user_id', $user->id)
            ->exists();
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
