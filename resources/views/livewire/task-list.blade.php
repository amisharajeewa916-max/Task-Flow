<div class="space-y-6">
    <!-- Message Banner -->
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 flex items-center justify-between shadow-sm border border-green-100 dark:border-green-800" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button @click="open = false" class="text-green-800 dark:text-green-400 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Controls Panel -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
        <!-- Live Search & Add Task -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks by title or description..." class="w-full pl-10 pr-4 py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:inline-flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                    <label for="project_filter" class="font-medium">Project:</label>
                    <select id="project_filter" wire:model.live="projectId" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs px-3 py-2">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Sorting selection -->
                <div class="flex items-center space-x-1.5 text-sm text-gray-600 dark:text-gray-400">
                    <span>Sort By:</span>
                    <button wire:click="toggleSort('deadline')" class="px-3 py-1.5 rounded-lg border {{ $sortBy === 'deadline' ? 'bg-indigo-50 dark:bg-indigo-900 border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-300' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:bg-gray-50' }} text-xs font-semibold flex items-center gap-1 transition">
                        Deadline
                        @if($sortBy === 'deadline')
                            <span>{!! $sortOrder === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                        @endif
                    </button>
                    <button wire:click="toggleSort('priority')" class="px-3 py-1.5 rounded-lg border {{ $sortBy === 'priority' ? 'bg-indigo-50 dark:bg-indigo-900 border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-300' : 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 hover:bg-gray-50' }} text-xs font-semibold flex items-center gap-1 transition">
                        Priority
                        @if($sortBy === 'priority')
                            <span>{!! $sortOrder === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                        @endif
                    </button>
                </div>

                @livewire('create-task')
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 dark:border-gray-700 pt-4 gap-4">
            <!-- Tabs -->
            <div class="flex flex-wrap items-center bg-gray-50 dark:bg-gray-900 p-1 rounded-xl w-fit">
                <button wire:click="$set('status', 'all')" class="px-4 py-2 text-xs font-bold rounded-lg transition duration-150 ease-in-out cursor-pointer {{ $status === 'all' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-550 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                    All
                </button>
                <button wire:click="$set('status', 'pending')" class="px-4 py-2 text-xs font-bold rounded-lg transition duration-150 ease-in-out cursor-pointer {{ $status === 'pending' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-550 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                    Pending
                </button>
                <button wire:click="$set('status', 'in_progress')" class="px-4 py-2 text-xs font-bold rounded-lg transition duration-150 ease-in-out cursor-pointer {{ $status === 'in_progress' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-550 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                    In Progress
                </button>
                <button wire:click="$set('status', 'completed')" class="px-4 py-2 text-xs font-bold rounded-lg transition duration-150 ease-in-out cursor-pointer {{ $status === 'completed' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-550 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                    Completed
                </button>
            </div>

            <!-- Priority Filter Dropdown -->
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Priority:</span>
                <select wire:model.live="priority" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs">
                    <option value="all">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tasks Grid/List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($tasks as $task)
            @livewire('task-card', ['task' => $task], key($task->id))
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No tasks found</p>
                <p class="text-xs text-gray-400 mt-1">Try expanding your search or filter options, or create a new task.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
</div>
