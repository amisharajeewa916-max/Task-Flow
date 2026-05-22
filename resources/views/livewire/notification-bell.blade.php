<div class="relative" x-data="{ open: false }">
    <!-- Bell Icon with Badge -->
    <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full transition duration-150 ease-in-out">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xxs font-bold leading-none text-white bg-rose-500 rounded-full transform translate-x-1/3 -translate-y-1/3">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown menu -->
    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 py-1 focus:outline-none z-50 divide-y divide-gray-100 dark:divide-gray-700" 
         style="display: none;">
        
        <div class="px-4 py-2 flex items-center justify-between">
            <span class="font-semibold text-sm text-gray-700 dark:text-gray-200">Notifications</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium">Mark all as read</button>
            @endif
        </div>

        <div class="max-h-64 overflow-y-auto">
            @forelse($unreadNotifications as $notif)
                <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition flex items-start justify-between gap-2">
                    <div class="flex-1">
                        <p class="text-xs text-gray-600 dark:text-gray-300">{{ $notif->message }}</p>
                        <span class="text-xxxxs text-gray-400 block mt-1">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <button wire:click="markAsRead({{ $notif->id }})" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 p-0.5 rounded" title="Mark as read">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-xs">
                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2.472M21 16v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m18 0H3" />
                    </svg>
                    No unread notifications
                </div>
            @endforelse
        </div>
    </div>
</div>
