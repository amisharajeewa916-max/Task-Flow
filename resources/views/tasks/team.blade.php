<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Team Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">All Collaborative Team Tasks</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">As a manager or admin, you can monitor and manage tasks created within your team workspaces.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-750 text-xxs font-bold text-gray-450 uppercase tracking-wider">
                                <th class="p-4 sm:p-5">Task Title</th>
                                <th class="p-4 sm:p-5">Project</th>
                                <th class="p-4 sm:p-5">Assignee</th>
                                <th class="p-4 sm:p-5">Deadline</th>
                                <th class="p-4 sm:p-5">Priority</th>
                                <th class="p-4 sm:p-5">Status</th>
                                <th class="p-4 sm:p-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-750 text-xs">
                            @forelse($tasks as $t)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                    <td class="p-4 sm:p-5 font-bold text-gray-900 dark:text-white">
                                        <a href="{{ route('tasks.show', $t->id) }}" class="hover:text-indigo-600 hover:underline">
                                            {{ $t->task_name }}
                                        </a>
                                    </td>
                                    <td class="p-4 sm:p-5 text-gray-500 dark:text-gray-400">
                                        {{ $t->project ? $t->project->project_name : 'Personal' }}
                                    </td>
                                    <td class="p-4 sm:p-5 text-gray-600 dark:text-gray-300">
                                        {{ $t->assignedUser ? $t->assignedUser->name : 'Unassigned' }}
                                    </td>
                                    <td class="p-4 sm:p-5 text-gray-500 {{ $t->status !== 'completed' && $t->deadline && $t->deadline->isPast() ? 'text-rose-500 font-bold' : '' }}">
                                        {{ $t->deadline ? $t->deadline->format('M d, Y') : 'No deadline' }}
                                    </td>
                                    <td class="p-4 sm:p-5">
                                        <span class="px-2 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $t->priority === 'high' ? 'bg-rose-50 text-rose-700' : ($t->priority === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                            {{ $t->priority }}
                                        </span>
                                    </td>
                                    <td class="p-4 sm:p-5">
                                        <span class="px-2 py-0.5 rounded-full text-xxxxs font-bold uppercase {{ $t->status === 'completed' ? 'bg-green-50 text-green-700' : ($t->status === 'in_progress' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700') }}">
                                            {{ str_replace('_', ' ', $t->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 sm:p-5 text-right space-x-1.5 flex justify-end items-center">
                                        <a href="{{ route('tasks.show', $t->id) }}" class="inline-flex items-center px-2 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-250 rounded-lg text-xxxxs font-bold transition">
                                            View
                                        </a>
                                        @can('update', $t)
                                            <a href="{{ route('tasks.edit', $t->id) }}" class="inline-flex items-center px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-650 rounded-lg text-xxxxs font-bold transition">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete', $t)
                                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-650 rounded-lg text-xxxxs font-bold transition cursor-pointer border border-transparent">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-500 dark:text-gray-400 italic">
                                        No team tasks available.
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
