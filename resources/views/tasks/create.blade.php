<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
                
                <div class="border-b border-gray-100 dark:border-gray-750 pb-4">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Create a New Task</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Fill in the fields below to create a new task.</p>
                </div>

                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4" x-data="{ taskType: '{{ old('task_type', 'normal') }}', projectId: '{{ old('project_id', '') }}' }">
                    @csrf

                    <!-- Task Title -->
                    <div>
                        <label for="task_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Task Title <span class="text-red-500">*</span></label>
                        <input type="text" name="task_name" id="task_name" value="{{ old('task_name') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Enter task title" required>
                        @error('task_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Describe the task...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Task Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Task Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex cursor-pointer rounded-xl border-2 p-4 transition-all duration-150"
                                :class="taskType === 'normal' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 bg-white hover:border-gray-300'">
                                <input type="radio" name="task_type" x-model="taskType" value="normal" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Normal Task</p>
                                        <p class="text-xs text-gray-400">Personal or individual task</p>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex cursor-pointer rounded-xl border-2 p-4 transition-all duration-150"
                                :class="taskType === 'team' ? 'border-violet-500 bg-violet-50' : 'border-gray-200 bg-white hover:border-gray-300'">
                                <input type="radio" name="task_type" x-model="taskType" value="team" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Team Task</p>
                                        <p class="text-xs text-gray-400">Shared across the team</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('task_type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                            <select name="priority" id="priority" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') === 'medium' || !old('priority') ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                            <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            @error('deadline') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Project Dropdown -->
                        <div>
                            <label for="project_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Project</label>
                            <select name="project_id" id="project_id" x-model="projectId" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">-- No Project --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->project_name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror

                            <div x-show="taskType === 'team' && !projectId" class="mt-2 flex items-start gap-2 text-xs text-amber-600 bg-amber-50 dark:bg-amber-950/20 p-2.5 rounded-lg border border-amber-200 dark:border-amber-900/50">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>
                                    <strong>Note:</strong> Without a project, this team task will only be visible to you and the assignee.
                                </span>
                            </div>
                        </div>

                        <!-- Assigned To User -->
                        <div>
                            <label for="assigned_to" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign User</label>
                            <select name="assigned_to" id="assigned_to" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">-- Unassigned --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                                @endforeach
                            </select>
                            @error('assigned_to') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-750 flex items-center justify-end space-x-3">
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                            Create Task
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
