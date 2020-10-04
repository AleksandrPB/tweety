<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'avatar', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *
     * @return string
     */
    public function getAvatarAttribute() //custom accessor
    {
        return ('https://api.adorable.io/avatars/150/' . $this->email);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class)
            ->latest();
    }

    //  $user->password = 'foobar'
    //  it will first be piped through this method
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }


    /**
     * @return mixed
     */
    public function timeline()
    {
        $friends = $this->follows()->pluck('id');

        return Tweet::whereIn('user_id', $friends)
            ->orWhere('user_id', $this->id)
            ->latest()
            ->paginate(50);
    }

    /**
     * @param string $append
     * @return string
     */
    public function path($append = '')
    {
        $path = route('profile', $this->username);
        return $append ? "{$path}/{$append}" : $path;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
