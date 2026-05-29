<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('New Project') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 space-y-6">
                
                <div class="border-b border-gray-100 dark:border-gray-750 pb-4">
                    <h3 class="text-lg font-bold text-gray-950 dark:text-white">Create a Collaborative Project</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Collaborate with your team members by housing tasks in a project.</p>
                </div>

                <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Project Name -->
                    <div>
                        <label for="project_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Project Name <span class="text-red-500">*</span></label>
                        <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Enter project name" required>
                        @error('project_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Describe this project...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            @error('start_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            @error('end_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Team Workspace -->
                    @if($teams->isEmpty())
                        <div>
                            <label for="new_team_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Team Workspace Name <span class="text-red-500">*</span></label>
                            <input type="text" name="new_team_name" id="new_team_name" value="{{ old('new_team_name') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Enter a team name to create" required>
                            @error('new_team_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">No team workspaces were found. A new team will be created automatically for this project.</p>
                        </div>
                    @else
                        <div>
                            <label for="team_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Associated Team Workspace <span class="text-red-500">*</span></label>
                            <select name="team_id" id="team_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" required>
                                <option value="">-- Select Team --</option>
                                @foreach($teams as $t)
                                    <option value="{{ $t->id }}" {{ old('team_id') == $t->id ? 'selected' : '' }}>{{ $t->team_name }}</option>
                                @endforeach
                            </select>
                            @error('team_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_team_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Create New Team Workspace (optional)</label>
                            <input type="text" name="new_team_name" id="new_team_name" value="{{ old('new_team_name') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="Enter a new team name if you want to create one">
                            @error('new_team_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-750 flex items-center justify-end space-x-3">
                        <a href="{{ route('projects.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                            Create Project
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
