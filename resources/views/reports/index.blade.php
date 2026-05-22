<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Progress Reports') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Workspace Analytics & Reports</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">As a manager or administrator, you can view the completion rates, priority distributions, and individual project metrics.</p>
                </div>
            </div>

            <!-- Analytics Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Completion Rate Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 flex flex-col justify-between space-y-4 shadow-sm">
                    <div class="space-y-1">
                        <span class="text-xs text-gray-450 uppercase font-bold tracking-wider">Overall Completion Rate</span>
                        <div class="text-3xl font-extrabold text-gray-950 dark:text-white font-outfit">{{ $completionRate }}%</div>
                    </div>
                    <!-- CSS Progress Bar -->
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-violet-600 h-2.5 rounded-full" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>

                <!-- Status Distribution Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 space-y-3.5 shadow-sm">
                    <span class="text-xs text-gray-450 uppercase font-bold tracking-wider block">Status Distribution</span>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">Completed</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $completedTasks }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">In Progress</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $inProgressTasks }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">Pending</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $pendingTasks }}</span>
                        </div>
                    </div>
                </div>

                <!-- Priority Distribution Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 space-y-3.5 shadow-sm">
                    <span class="text-xs text-gray-450 uppercase font-bold tracking-wider block">Priority Distribution</span>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">High</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $highPriority }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">Medium</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $mediumPriority }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-550">Low</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $lowPriority }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Progress Breakdown -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-950 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-4">Project Completion Status</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-150 dark:border-gray-750 text-xxs font-bold text-gray-450 uppercase tracking-wider">
                                <th class="p-4">Project Name</th>
                                <th class="p-4">Total Tasks</th>
                                <th class="p-4">Completed Tasks</th>
                                <th class="p-4">Completion Rate</th>
                                <th class="p-4 text-right">Progress Bar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-750 text-xs">
                            @forelse($projects as $p)
                                @php
                                    $rate = $p->tasks_count > 0 ? round(($p->completed_tasks_count / $p->tasks_count) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                    <td class="p-4 font-bold text-gray-900 dark:text-white">
                                        <a href="{{ route('projects.show', $p->id) }}" class="hover:text-indigo-650 hover:underline">
                                            {{ $p->project_name }}
                                        </a>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $p->tasks_count }}</td>
                                    <td class="p-4 text-gray-500">{{ $p->completed_tasks_count }}</td>
                                    <td class="p-4 font-bold text-gray-850 dark:text-gray-250">{{ $rate }}%</td>
                                    <td class="p-4 text-right">
                                        <div class="w-36 bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden ml-auto">
                                            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 italic">
                                        No active projects to report.
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
