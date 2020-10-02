<x-app>
    <header class="mb-6 relative">
        <div class="relative">
            <img class="mb-2"
                 src="/images/default-profile-banner.jpeg"
                 alt=""
            >
            <img
                src="{{ $user->getAvatarAttribute() }}"
                alt=""
                class="rounded-full mr-2 absolute bottom-0 transform -translate-x-1/2 translate-y-1/2"
                style="left: 50%"
                width="200"
                height="200"
            >
        </div>

        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="font-bold text-2xl mb-0">{{ $user->name }}</h2>
                <p class="text-sm">Joined {{ $user->created_at->diffForHumans() }}</p>
            </div>

            <div class="flex">
                @can('edit', $user)
                <a
                    href="{{ $user->path('edit') }}"
                    class="rounded-full border border-gray-300 py-4 px-2 text-black text-l mr-2"
                >
                    Edit Profile
                </a>
                @endcan
                <x-follow-button :user="$user"></x-follow-button>
            </div>
        </div>
        <p class="text-small mt-2">
            Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation —
            freeing you to create without sweating the small things.
        </p>
    </header>

    @include('_timeline',[
    'tweets' => $user->tweets
    ])
</x-app>
