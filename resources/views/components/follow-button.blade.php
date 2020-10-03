@unless(current_user()->is($user))
<form method="POST"
      action="{{ route('follow', $user->username) }}">
    @csrf

    <button
        type="submit"
        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
    >
        {{current_user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
    </button>
</form>
@endunless
