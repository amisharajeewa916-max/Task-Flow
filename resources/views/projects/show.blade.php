<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
                {{ __('Project Details') }}
            </h2>
            <div class="flex items-center space-x-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Edit Project
                    </a>
                @endcan
                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-950 text-white font-medium text-sm rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition cursor-pointer">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Project Main Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
                <div class="flex justify-between items-start gap-4">
                    <div class="space-y-1">
                        <span class="px-2.5 py-0.5 rounded-full text-xxxxs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/35 dark:text-indigo-300 border border-indigo-200">
                            Team: {{ $project->team->team_name }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-outfit font-extrabold text-gray-950 dark:text-white pt-2">
                            {{ $project->project_name }}
                        </h1>
                    </div>

                    <div class="text-right text-xs text-gray-500 space-y-1">
                        <div><span class="font-medium text-gray-400">Creator:</span> <span class="font-bold text-gray-750">{{ $project->creator->name }}</span></div>
                        @if($project->start_date)
                            <div><span class="font-medium text-gray-400">Duration:</span> <span class="font-bold text-gray-750">{{ $project->start_date->format('M d, Y') }} - {{ $project->end_date ? $project->end_date->format('M d, Y') : 'Ongoing' }}</span></div>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Project Description</h3>
                    <div class="text-sm text-gray-650 dark:text-gray-300 leading-relaxed whitespace-pre-line bg-gray-50/50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-750 p-4 rounded-xl">
                        {{ $project->description ?: 'No description provided for this project.' }}
                    </div>
                </div>
            </div>

            <!-- Task Breakdown -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-750 pb-4">
                    <h3 class="text-base font-bold text-gray-950 dark:text-white">Project Tasks ({{ $project->tasks->count() }})</h3>
                    @can('update', $project)
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm transition cursor-pointer">
                            + Add Task to Project
                        </a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-150 dark:border-gray-750 text-xxs font-bold text-gray-450 uppercase tracking-wider">
                                <th class="p-4">Task Name</th>
                                <th class="p-4">Assignee</th>
                                <th class="p-4">Deadline</th>
                                <th class="p-4">Priority</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-750 text-xs">
                            @forelse($project->tasks as $task)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                    <td class="p-4 font-bold text-gray-900 dark:text-white">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="hover:text-indigo-650 hover:underline">
                                            {{ $task->task_name }}
                                        </a>
                                    </td>
                                    <td class="p-4 text-gray-600 dark:text-gray-300">
                                        {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}
                                        @if($task->status === 'completed' && $task->assignedUser)
                                            <span class="text-green-600 font-bold ml-1" title="Assignment completed">(Done)</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-500 {{ $task->status !== 'completed' && $task->deadline && $task->deadline->isPast() ? 'text-rose-500 font-bold' : '' }}">
                                        {{ $task->deadline ? $task->deadline->format('M d, Y') : 'No deadline' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $task->priority === 'high' ? 'bg-rose-50 text-rose-700' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $task->status === 'completed' ? 'bg-green-50 text-green-700' : ($task->status === 'in_progress' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700') }}">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right space-x-2">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-2.5 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-250 rounded-lg text-xxxxs font-bold text-gray-650 transition">
                                            View
                                        </a>
                                        @can('update', $task)
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-650 rounded-lg text-xxxxs font-bold transition">
                                                Edit
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400 italic">
                                        No tasks created under this project yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
