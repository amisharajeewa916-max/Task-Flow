<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <div class="flex items-center space-x-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Edit Task
                    </a>
                @endcan
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-950 text-white font-medium text-sm rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition cursor-pointer">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Message Banner -->
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-150 shadow-sm flex items-center justify-between" role="alert">
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Pane: Details & Description -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Task main card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Status Badges -->
                            @if($task->status === 'completed')
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-300 border border-green-200">
                                    Completed
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 border border-indigo-200">
                                    In Progress
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200">
                                    Pending
                                </span>
                            @endif

                            <!-- Priority Badges -->
                            @if($task->priority === 'high')
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200">
                                    High Priority
                                </span>
                            @elseif($task->priority === 'medium')
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200">
                                    Medium Priority
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200">
                                    Low Priority
                                </span>
                            @endif

                            <!-- Project badge -->
                            @if($task->project)
                                <span class="px-2.5 py-0.5 rounded-full text-xxs font-bold bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300 border border-gray-200">
                                    Project: {{ $task->project->project_name }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-outfit font-extrabold text-gray-950 dark:text-white">
                            {{ $task->task_name }}
                        </h1>

                        <div class="space-y-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Description</h3>
                            <div class="text-sm text-gray-650 dark:text-gray-300 leading-relaxed whitespace-pre-line bg-gray-50/50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-750 p-4 rounded-xl">
                                {{ $task->description ?: 'No description provided for this task.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Livewire Comments Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                        @livewire('comment-section', ['task' => $task])
                    </div>
                </div>

                <!-- Right Pane: Meta Details & Attachments -->
                <div class="space-y-8">
                    <!-- Task Meta Properties -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                        <h3 class="text-sm font-bold text-gray-950 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-3">Properties</h3>
                        
                        <div class="space-y-3.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-450 font-medium">Deadline</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200 {{ $task->status !== 'completed' && $task->deadline && $task->deadline->isPast() ? 'text-rose-500 font-extrabold animate-pulse' : '' }}">
                                    {{ $task->deadline ? $task->deadline->format('M d, Y') : 'No deadline set' }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-450 font-medium">Assigned To</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}
                                    @if($task->status === 'completed' && $task->assignedUser)
                                        <span class="text-green-600 font-bold ml-1" title="Assignment completed">(Done)</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-450 font-medium">Created By</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $task->creator->name }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-450 font-medium">Created At</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $task->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>

                            @if($task->status === 'completed')
                                <div class="mt-4 flex items-center justify-between bg-green-50/50 dark:bg-green-950/20 p-2.5 rounded-xl border border-green-150/40 text-xs">
                                    <span class="text-green-600 dark:text-green-400 font-semibold">Assignment Done</span>
                                    <span class="font-bold text-green-700 dark:text-green-300 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Completed
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Secure File Attachments Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                        <h3 class="text-sm font-bold text-gray-950 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-3">Attachments</h3>

                        <!-- Attachments list -->
                        <div class="space-y-3 max-h-48 overflow-y-auto pr-1">
                            @forelse($task->attachments as $attach)
                                <div class="flex items-center justify-between p-2.5 bg-gray-50/50 hover:bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-750 rounded-xl transition text-xs gap-3">
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 dark:text-gray-200 truncate" title="{{ $attach->file_name }}">
                                            {{ $attach->file_name }}
                                        </p>
                                        <span class="text-xxxxs text-gray-400 block mt-0.5">Uploaded by {{ $attach->user->name }}</span>
                                    </div>
                                    <a href="{{ route('attachments.download', $attach->id) }}" class="p-1.5 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 bg-white dark:bg-gray-800 border border-gray-250 dark:border-gray-650 rounded-lg shadow-xxs transition-colors" title="Download secure file">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            @empty
                                <p class="text-xs text-gray-450 italic text-center py-2">No attachments uploaded.</p>
                            @endforelse
                        </div>

                        <!-- Upload Attachment Form -->
                        @can('update', $task)
                            <form action="{{ route('tasks.attachments.upload', $task->id) }}" method="POST" enctype="multipart/form-data" class="pt-3 border-t border-gray-150 dark:border-gray-750/70 space-y-2">
                                @csrf
                                <label class="block text-xxs font-bold text-gray-400 uppercase">Upload Secure File</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" name="file" class="block w-full text-xxs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xxs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 hover:file:bg-indigo-100 transition cursor-pointer" required>
                                    <button type="submit" class="px-3 py-1.5 bg-indigo-650 hover:bg-indigo-750 text-white rounded-lg text-xxs font-bold shadow-xxs cursor-pointer">
                                        Upload
                                    </button>
                                </div>
                                <span class="text-xxxxs text-gray-450 block italic">Allowed: JPG, PNG, PDF, DOC, DOCX, ZIP (Max 5MB)</span>
                                @error('file') <span class="text-xxs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
