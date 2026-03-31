{{-- resources/views/admin/departments/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'New Department')
@section('page-title', 'New Department')

@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Department Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       placeholder="e.g. Information Technology"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                              focus:ring-2 focus:ring-indigo-500
                              {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                          placeholder="Brief description of this department..."
                          class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                                 focus:ring-2 focus:ring-indigo-500 resize-none
                                 {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                           px-6 py-2.5 rounded-xl transition shadow-sm">
                    Create Department
                </button>
                <a href="{{ route('admin.departments.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>

@endsection