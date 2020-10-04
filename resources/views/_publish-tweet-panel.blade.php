<div class="border border-blue-400 rounded-lg p-8 mb-8">
    <form method="post" action="/tweets">
        @csrf
                    <textarea
                        name="body"
                        class="w-full"
                        placeholder="What's up doc?"
                        required
                        autofocus
                    ></textarea>
        <hr class="my-4">
        <footer class="flex justify-between items-center">
            <img
                src="{{auth()->user()->avatar}}"
                alt=""
                class="rounded-full mr-2"
                width="50"
                height="50"
            >
            <button
                type="submit"
                class="bg-blue-400 shadow px-10 h-10 text-white text-sm rounded-lg hover:bg-blue-500"
            >Tweet-a-roo!
            </button>
        </footer>
    </form>

    @error('body')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
