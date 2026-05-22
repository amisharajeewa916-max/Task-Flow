<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between transition-all duration-200 relative group overflow-hidden">
    <!-- Header -->
    <div>
        <div class="flex justify-between items-start mb-3 gap-2">
            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-1.5">
                <!-- Status Badge -->
                @if($task->status === 'completed')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-green-50 text-green-700 dark:bg-green-900/35 dark:text-green-300 border border-green-200/50">
                        Completed
                    </span>
                @elseif($task->status === 'in_progress')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/35 dark:text-indigo-300 border border-indigo-200/50">
                        In Progress
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-amber-50 text-amber-700 dark:bg-amber-900/35 dark:text-amber-300 border border-amber-200/50">
                        Pending
                    </span>
                @endif

                <!-- Priority Badge -->
                @if($task->priority === 'high')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-rose-50 text-rose-700 dark:bg-rose-900/35 dark:text-rose-300 border border-rose-200/50">
                        High
                    </span>
                @elseif($task->priority === 'medium')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-amber-50 text-amber-700 dark:bg-amber-900/35 dark:text-amber-300 border border-amber-200/50">
                        Medium
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxxxs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/35 dark:text-emerald-300 border border-emerald-200/50">
                        Low
                    </span>
                @endif
            </div>

            <!-- Due Date Warning if overdue -->
            @if($task->deadline)
                <div class="text-xxxxs font-bold flex items-center {{ $task->status !== 'completed' && $task->deadline->isPast() ? 'text-rose-500' : 'text-gray-400 dark:text-gray-500' }}">
                    <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $task->deadline->format('M d, Y') }}</span>
                    @if($task->status !== 'completed' && $task->deadline->isPast())
                        <span class="ml-1 uppercase text-xxxxs">[Overdue]</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Task Title -->
        <a href="{{ route('tasks.show', $task->id) }}" class="block mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
            <h4 class="text-base font-bold text-gray-950 dark:text-white line-clamp-1 {{ $task->status === 'completed' ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                {{ $task->task_name }}
            </h4>
        </a>

        <!-- Description -->
        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed">
            {{ $task->description ?: 'No description provided.' }}
        </p>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 flex items-center justify-between">
        <!-- Assignee Info -->
        <div class="flex items-center space-x-2">
            <div class="flex-shrink-0">
                @if($task->assignedUser)
                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xxxxs font-bold uppercase shadow-sm" title="Assigned to {{ $task->assignedUser->name }}">
                        {{ substr($task->assignedUser->name, 0, 2) }}
                    </span>
                @else
                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 dark:bg-gray-750 text-gray-400 text-xxxxs font-bold uppercase border border-dashed border-gray-300 dark:border-gray-600" title="Unassigned">
                        --
                    </span>
                @endif
            </div>
            <div class="hidden sm:block text-xxs font-medium text-gray-600 dark:text-gray-400">
                @if($task->assignedUser)
                    <span class="line-clamp-1">
                        {{ $task->assignedUser->name }}
                        @if($task->status === 'completed')
                            <span class="text-green-600 font-bold ml-1" title="Assignment completed">(Done)</span>
                        @endif
                    </span>
                @else
                    <span class="text-gray-450 italic">Unassigned</span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <!-- Status Buttons -->
            @can('update', $task)
                {{-- In Progress Button --}}
                <button
                    wire:click="setStatus('in_progress')"
                    title="{{ $task->status === 'in_progress' ? 'Currently In Progress' : 'Mark as In Progress' }}"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xxxxs font-semibold border transition-all duration-150 cursor-pointer
                        {{ $task->status === 'in_progress'
                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm shadow-indigo-200 dark:shadow-none'
                            : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}"
                >
                    {{-- Spinner / active dot --}}
                    @if($task->status === 'in_progress')
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="h-3 w-3 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                    In Progress
                </button>

                {{-- Done Button --}}
                <button
                    wire:click="setStatus('completed')"
                    title="{{ $task->status === 'completed' ? 'Completed — click to reopen' : 'Mark as Done' }}"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xxxxs font-semibold border transition-all duration-150 cursor-pointer
                        {{ $task->status === 'completed'
                            ? 'bg-green-600 text-white border-green-600 shadow-sm shadow-green-200 dark:shadow-none'
                            : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:border-green-400 hover:text-green-600 dark:hover:text-green-400' }}"
                >
                    @if($task->status === 'completed')
                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <svg class="h-3 w-3 shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                    Done
                </button>
            @endcan

            <!-- Details Link -->
            <a href="{{ route('tasks.show', $task->id) }}" class="ml-auto p-1.5 rounded-lg border text-gray-450 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/45 border-gray-200 dark:border-gray-700 transition" title="View details">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>

            <!-- Delete Button -->
            @can('delete', $task)
                <button wire:confirm="Are you sure you want to delete this task?" wire:click="deleteTask" class="p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-450 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/45 transition cursor-pointer" title="Delete task">
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            @endcan
        </div>
    </div>
</div>
