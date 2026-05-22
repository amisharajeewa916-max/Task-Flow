<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
                {{ __('Projects') }}
            </h2>
            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-medium text-sm rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out cursor-pointer">
                    <svg class="w-5 h-5 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Project
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-150 shadow-sm" role="alert">
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $p)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between transition-all duration-200">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xxxxs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/35 dark:text-indigo-300 border border-indigo-200">
                                    {{ $p->team->team_name }}
                                </span>
                                @if($p->end_date)
                                    <span class="text-xxxxs text-gray-400 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ends: {{ $p->end_date->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('projects.show', $p->id) }}" class="block mb-2 hover:text-indigo-600 transition-colors">
                                <h4 class="text-base font-bold text-gray-950 dark:text-white line-clamp-1">
                                    {{ $p->project_name }}
                                </h4>
                            </a>

                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed mb-6">
                                {{ $p->description ?: 'No description provided for this project.' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-750 pt-4 flex items-center justify-between">
                            <span class="text-xxxxs text-gray-400 block">Created by {{ $p->creator->name }}</span>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('projects.show', $p->id) }}" class="px-2.5 py-1 bg-gray-55 hover:bg-gray-100 border border-gray-250 rounded-lg text-xxxxs font-bold text-gray-650 transition">
                                    View
                                </a>
                                @can('update', $p)
                                    <a href="{{ route('projects.edit', $p->id) }}" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-650 rounded-lg text-xxxxs font-bold transition">
                                        Edit
                                    </a>
                                @endcan
                                @can('delete', $p)
                                    <form action="{{ route('projects.destroy', $p->id) }}" method="POST" wire:confirm="Are you sure you want to delete this project?" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-650 rounded-lg text-xxxxs font-bold transition cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-12 text-center text-gray-500">
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No projects created yet.</p>
                        @can('create', App\Models\Project::class)
                            <p class="text-xs text-gray-400 mt-1">Get started by creating a collaborative project space for your team.</p>
                        @endcan
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
