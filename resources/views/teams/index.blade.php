<x-app-layout>
    <x-slot name="header">
        <h2 class="font-outfit font-extrabold text-xl text-gray-900 leading-tight">
            {{ __('Team Workspaces') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session()->has('message'))
                <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-150 shadow-sm" role="alert">
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-150 shadow-sm" role="alert">
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                <!-- Create Team Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <h3 class="text-base font-bold text-gray-950 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-3">Create a New Team Workspace</h3>
                    
                    <form action="{{ route('teams.store') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
                        @csrf
                        <div class="flex-1 w-full">
                            <label for="team_name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Team Name</label>
                            <input type="text" name="team_name" id="team_name" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" placeholder="e.g. Frontend Devs, Study Group" required>
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer w-full sm:w-auto">
                            Create Team
                        </button>
                    </form>
                </div>
            @endif

            <!-- Team Workspaces List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($teams as $team)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-750 pb-4 mb-4 gap-2">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-gray-950 dark:text-white">
                                        {{ $team->team_name }}
                                    </h4>
                                    <p class="text-xxxxs text-gray-400 font-medium">Created by {{ $team->creator->name }} on {{ $team->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <!-- Members List -->
                            <div class="space-y-3">
                                <h5 class="text-xxs font-bold uppercase tracking-wider text-gray-400">Members ({{ $team->users->count() }})</h5>
                                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                    @foreach($team->users as $u)
                                        <div class="flex items-center justify-between p-2 bg-gray-50/50 hover:bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-750 rounded-xl transition text-xs gap-3">
                                            <div class="flex items-center space-x-2 min-w-0">
                                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-indigo-50 text-indigo-700 text-xxxxs font-bold uppercase">
                                                    {{ substr($u->name, 0, 2) }}
                                                </span>
                                                <span class="font-bold text-gray-900 dark:text-white truncate">{{ $u->name }} ({{ ucfirst($u->role) }})</span>
                                            </div>
                                            <!-- Remove Member action -->
                                            @if(($team->created_by === Auth::id() || Auth::user()->isAdmin()) && $team->created_by !== $u->id)
                                                <form action="{{ route('teams.members.remove', [$team->id, $u->id]) }}" method="POST" wire:confirm="Are you sure you want to remove this member?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-xxs transition cursor-pointer">
                                                        Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Add Member Panel -->
                        @if($team->created_by === Auth::id() || Auth::user()->isAdmin())
                            <form action="{{ route('teams.members.add', $team->id) }}" method="POST" class="pt-4 border-t border-gray-100 dark:border-gray-750 flex items-center gap-3">
                                @csrf
                                <div class="flex-1">
                                    <select name="user_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-150 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs" required>
                                        <option value="">-- Add Member --</option>
                                        @foreach($allUsers as $user)
                                            @if(!$team->users->contains($user->id))
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-950 border border-transparent rounded-lg text-xs font-bold text-white shadow-sm transition cursor-pointer">
                                    Add
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-12 text-center text-gray-500">
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">You are not a member of any team workspaces yet.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
