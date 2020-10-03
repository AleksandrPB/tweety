<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', compact('user'));
    }

    public function edit(User $user)
    {
//        $this->authorize('edit', $user);
        return view('profiles.edit', compact('user'));
    }

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
            'avatar' => [
                'file'
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

        if (request('avatar')) {
            $attributes['avatar'] = request('avatar')->store('public/storage/avatars');
        }

        $user->update($attributes);

        return redirect($user->path());
    }

}
