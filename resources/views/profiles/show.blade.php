<x-app>
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

            <div class="flex">
                <a
                    href=""
                    class="rounded-full border border-gray-300 py-4 px-2 text-black text-l mr-2"
                >
                    Edit Profile
                </a>
                <form method="post" action="/profiles/{{ $user->name }}/follow">
                    @csrf

                    <button
                        type="submit"
                        class="bg-blue-500 rounded-full shadow py-4 px-2 text-white texts-xs"
                    >
                        {{auth()->user()->following($user) ? 'Unfollow Me' : 'Follow Me'}}
                    </button>
                </form>
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
</x-app>
