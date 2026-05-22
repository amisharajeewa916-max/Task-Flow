<div class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
        <svg class="h-5.5 w-5.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        Comments ({{ $comments->count() }})
    </h3>

    <!-- Post Comment Form -->
    @auth
        <form wire:submit.prevent="submitComment" class="space-y-3">
            <div>
                <label for="comment_text" class="sr-only">Your comment</label>
                <textarea id="comment_text" wire:model.defer="comment_text" rows="3" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Write a comment..." required></textarea>
                @error('comment_text') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xxxxs text-gray-450 italic">Be respectful and clear.</span>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-semibold text-xs rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                    Post Comment
                </button>
            </div>
        </form>
    @else
        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl text-center text-xs text-gray-500">
            Please <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">log in</a> to post comments.
        </div>
    @endauth

    <!-- Comments List -->
    <div class="space-y-4 max-h-96 overflow-y-auto pr-2 divide-y divide-gray-100 dark:divide-gray-700/60">
        @forelse($comments as $comment)
            <div class="pt-4 first:pt-0 flex items-start gap-3">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xs font-bold uppercase shadow-sm">
                        {{ substr($comment->user->name, 0, 2) }}
                    </span>
                </div>
                <!-- Content -->
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                        <span class="text-xxxxs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $comment->comment_text }}
                    </p>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-xs text-gray-450 italic">
                No comments posted yet.
            </div>
        @endforelse
    </div>
</div>
