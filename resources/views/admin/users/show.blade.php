@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-navy-700">User Details</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- User Info Card -->
                    <div class="md:col-span-2">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center mb-6">
                                <div class="h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center">
                                    <span class="text-teal-800 text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-6">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Role</h4>
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-purple-100 text-purple-800',
                                            'leader_teknisi' => 'bg-blue-100 text-blue-800',
                                            'leader_operator' => 'bg-green-100 text-green-800',
                                        ];
                                        $roleLabels = [
                                            'admin' => 'Administrator',
                                            'leader_teknisi' => 'Leader Teknisi',
                                            'leader_operator' => 'Leader Operator',
                                        ];
                                    @endphp
                                    <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $roleLabels[$user->role] ?? $user->role }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Account Created</h4>
                                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Last Updated</h4>
                                    <p class="mt-1 text-gray-900">{{ $user->updated_at->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Email Verified</h4>
                                    <p class="mt-1">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center text-green-600">
                                                <i class="fas fa-check-circle mr-2"></i>Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-yellow-600">
                                                <i class="fas fa-exclamation-circle mr-2"></i>Not Verified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="block w-full text-center px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Edit User
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-red-700 hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash mr-2"></i>Delete User
                                    </button>
                                </form>

                                <a href="mailto:{{ $user->email }}" 
                                   class="block w-full text-center px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>Send Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
