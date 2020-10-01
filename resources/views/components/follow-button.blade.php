<form method="post" action="/profiles/{{ $user->name }}/follow">
    @csrf

    <button
        type="submit"
        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
    >
        {{auth()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
    </button>
</form>
