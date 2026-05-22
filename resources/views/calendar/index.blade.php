<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Calendar Deadlines') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Deadline Tracking Calendar</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Keep track of upcoming deadlines sorted chronologically to optimize completion timing.</p>
                </div>
            </div>

            <!-- List of tasks ordered by deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tasks as $t)
                    <div class="bg-white dark:bg-gray-800 border {{ $t->status !== 'completed' && $t->deadline->isPast() ? 'border-rose-350 dark:border-rose-900' : 'border-gray-100 dark:border-gray-700' }} rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $t->priority === 'high' ? 'bg-rose-50 text-rose-700' : ($t->priority === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                    {{ $t->priority }}
                                </span>
                                <span class="text-xxs font-bold flex items-center {{ $t->status !== 'completed' && $t->deadline->isPast() ? 'text-rose-500 font-extrabold' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $t->deadline->format('M d, Y') }}
                                    @if($t->status !== 'completed' && $t->deadline->isPast())
                                        <span class="ml-1 uppercase text-xxxxs font-black">[Overdue]</span>
                                    @endif
                                </span>
                            </div>

                            <a href="{{ route('tasks.show', $t->id) }}" class="block mb-2 hover:underline">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 {{ $t->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                                    {{ $t->task_name }}
                                </h4>
                            </a>

                            <p class="text-xxs text-gray-500 dark:text-gray-450 line-clamp-2 leading-relaxed mb-4">
                                {{ $t->description ?: 'No description provided.' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-750 pt-3 flex items-center justify-between">
                            <span class="text-xxxxs text-gray-400">Assignee: {{ $t->assignedUser ? $t->assignedUser->name : 'Unassigned' }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $t->status === 'completed' ? 'bg-green-50 text-green-700' : ($t->status === 'in_progress' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700') }}">
                                {{ $t->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-12 text-center text-gray-500">
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No deadline-associated tasks found.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
