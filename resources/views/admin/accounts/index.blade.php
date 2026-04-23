@extends('layouts.admin')

@section('title', 'Manage Accounts')
@section('page-title', 'User Accounts')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $users->total() }} user(s) found</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Force Pwd Change</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 text-xs font-bold ring-1 ring-inset ring-brand-700/10 shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($user->hasRole('admin')) bg-purple-50 text-purple-700 
                            @elseif($user->hasRole('trainer')) bg-blue-50 text-blue-700 
                            @elseif($user->hasRole('student')) bg-orange-50 text-orange-700 
                            @else bg-gray-50 text-gray-700 @endif capitalize">
                            {{ $user->role ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form method="POST" action="{{ route('admin.accounts.toggle-must-change', $user) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center">
                                @if($user->must_change_password)
                                    <span class="bg-red-50 text-red-700 px-2.5 py-0.5 rounded-full text-xs font-medium border border-red-100">Yes</span>
                                @else
                                    <span class="bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-medium border border-green-100">No</span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.accounts.reset-password', $user) }}" onsubmit="return confirm('Reset password for {{ $user->name }} to \'password\'?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    Reset Pwd
                                </button>
                            </form>
                            @if(Auth::id() !== $user->id)
                            <form method="POST" action="{{ route('admin.accounts.destroy', $user) }}" onsubmit="return confirm('Delete user account and linked profile for {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
