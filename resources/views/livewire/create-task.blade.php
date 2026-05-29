<div>
    <!-- Button Trigger if open from dashboard or parent directly -->
    <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-medium text-sm rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out cursor-pointer">
        <svg class="w-5 h-5 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Task
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-80" wire:click="closeModal"></div>

                <!-- Spacer to center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-150 dark:border-gray-700">
                    <form wire:submit.prevent="submit">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">
                                Create New Task
                            </h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-4">
                            <!-- Task Name -->
                            <div>
                                <label for="task_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Task Title <span class="text-red-500">*</span></label>
                                <input type="text" id="task_name" wire:model="task_name" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Enter task title" required>
                                @error('task_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea id="description" wire:model="description" rows="3" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Describe the task..."></textarea>
                                @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Task Type Selector -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Task Type <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative flex cursor-pointer rounded-xl border-2 p-3 transition-all duration-150
                                        {{ $task_type === 'normal' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-950/30' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:border-gray-300' }}">
                                        <input type="radio" wire:model.live="task_type" value="normal" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 {{ $task_type === 'normal' ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <div>
                                                <p class="text-xs font-bold {{ $task_type === 'normal' ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">Normal</p>
                                                <p class="text-xxxxs text-gray-400">Personal task</p>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer rounded-xl border-2 p-3 transition-all duration-150
                                        {{ $task_type === 'team' ? 'border-violet-500 bg-violet-50 dark:bg-violet-950/30' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 hover:border-gray-300' }}">
                                        <input type="radio" wire:model.live="task_type" value="team" class="sr-only">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 {{ $task_type === 'team' ? 'text-violet-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div>
                                                <p class="text-xs font-bold {{ $task_type === 'team' ? 'text-violet-700 dark:text-violet-300' : 'text-gray-700 dark:text-gray-300' }}">Team</p>
                                                <p class="text-xxxxs text-gray-400">Shared task</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('task_type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Priority -->
                                <div>
                                    <label for="priority" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                                    <select id="priority" wire:model="priority" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    @error('priority') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Deadline -->
                                <div>
                                    <label for="deadline" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deadline</label>
                                    <input type="date" id="deadline" wire:model="deadline" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                    @error('deadline') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Project Selection -->
                                <div>
                                    <label for="project_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Project</label>
                                    <select id="project_id" wire:model.live="project_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                        <option value="">-- No Project --</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    
                                    @if ($task_type === 'team' && !$project_id)
                                        <div class="mt-2 flex items-start gap-2 text-xs text-amber-600 bg-amber-50 dark:bg-amber-950/20 p-2.5 rounded-lg border border-amber-200 dark:border-amber-900/50">
                                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span>
                                                <strong>Note:</strong> Without a project, this team task will only be visible to you and the assignee.
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- User Assignment -->
                                <div>
                                    <label for="assigned_to" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Assign User</label>
                                    <select id="assigned_to" wire:model="assigned_to" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                        <option value="">-- Unassigned --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3 rounded-b-2xl">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
