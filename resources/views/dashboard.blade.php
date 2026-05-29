<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Message Banner -->
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-150 shadow-sm flex items-center justify-between" role="alert">
                    <span class="font-medium font-outfit">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Welcome Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-outfit font-extrabold text-gray-950 dark:text-white">
                        Hi {{ Auth::user()->name }}, Welcome To TaskFlow!
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Here's your work breakdown for today. You are logged in as a <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase text-xs">{{ Auth::user()->role }}</span>.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @livewire('create-task')
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Tasks -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-650 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-outfit">{{ $totalTasks }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Tasks</div>
                    </div>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-amber-650 dark:text-amber-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-outfit">{{ $pendingTasks }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Tasks</div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-650 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-outfit">{{ $completedTasks }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completed Tasks</div>
                    </div>
                </div>

                <!-- Overdue Tasks -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center space-x-4">
                    <div class="p-3 bg-rose-50 dark:bg-rose-900/30 rounded-xl text-rose-650 dark:text-rose-450">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-outfit">{{ $overdueTasks }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Overdue Tasks</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Pane: Latest Tasks -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-750 pb-4">
                        <h3 class="text-base font-bold text-gray-950 dark:text-white">Your Latest Tasks</h3>
                        <a href="{{ route('tasks.index') }}" class="text-xs text-indigo-600 hover:text-indigo-500 font-semibold transition">View All Tasks &rarr;</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($latestTasks as $task)
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 hover:bg-gray-50 dark:bg-gray-900/40 dark:hover:bg-gray-900 border border-gray-100 dark:border-gray-750 rounded-xl transition gap-4">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <span class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:underline block truncate">
                                            {{ $task->task_name }}
                                        </a>
                                        <span class="text-xxxxs text-gray-400 block mt-0.5">
                                            @if($task->project)
                                                Project: {{ $task->project->project_name }}
                                            @else
                                                Personal
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <span class="px-2.5 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $task->status === 'completed' ? 'bg-green-50 text-green-700' : ($task->status === 'in_progress' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700') }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-gray-450 italic">
                                No tasks available. Get started by adding a task!
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Pane: Summary/Projects info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
                    <div class="border-b border-gray-100 dark:border-gray-750 pb-4">
                        <h3 class="text-base font-bold text-gray-950 dark:text-white">Workspace Overview</h3>
                    </div>

                    <div class="space-y-4 text-sm">
                        <!-- Projects Count -->
                        <div class="flex items-center justify-between p-3.5 bg-gray-50/55 dark:bg-gray-900/30 rounded-xl">
                            <span class="font-medium text-gray-650 dark:text-gray-300">Active Projects</span>
                            <span class="font-extrabold text-gray-900 dark:text-white font-outfit">{{ $projectsCount }}</span>
                        </div>

                        <!-- Teams Count -->
                        <div class="flex items-center justify-between p-3.5 bg-gray-50/55 dark:bg-gray-900/30 rounded-xl">
                            <span class="font-medium text-gray-650 dark:text-gray-300">Collaborative Teams</span>
                            <span class="font-extrabold text-gray-900 dark:text-white font-outfit">{{ $teamsCount }}</span>
                        </div>

                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <!-- Users Count -->
                            <div class="flex items-center justify-between p-3.5 bg-gray-50/55 dark:bg-gray-900/30 rounded-xl">
                                <span class="font-medium text-gray-650 dark:text-gray-300">Connected Users</span>
                                <span class="font-extrabold text-gray-900 dark:text-white font-outfit">{{ $usersCount }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-750 text-center">
                        <p class="text-xxxxs text-gray-400">
                            TaskFlow SaaS — COMP50016 University Assignment
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
