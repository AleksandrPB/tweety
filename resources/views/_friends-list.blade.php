<div class="px-12 py-4 bg-gray-200 border border-gray-300 rounded-lg">
    <h3 class="font-bold text-xl mb-4">Following</h3>
        <ul>
            @forelse(auth()->user()->follows as $user)
                <li class="{{ $loop->last ? '' : 'mb-4'}}">
                    <div>
                        <a href="{{ route('profile', $user) }}" class="flex items-center text-sm">
                            <img
                                src={{ $user->avatar }}
                                    alt=""
                                class="rounded-full mr-2"
                                width="40"
                                height="40"
                            >
                            {{ $user->name }}
                        </a>
                    </div>
                    @empty
                        <p class="p-4">No friends yet.</p>
                <li>
            @endforelse
        </ul>
</div>

