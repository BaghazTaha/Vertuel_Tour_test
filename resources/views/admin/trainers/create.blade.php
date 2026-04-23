@extends('layouts.admin')

@section('title', 'New Trainer')
@section('page-title', 'Create Trainer')

@section('content')

<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('admin.trainers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" required value="{{ old('first_name') }}"
                           class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('first_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}"
                           class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('last_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            </div>

            <!-- Sex -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender / Sexe <span class="text-red-500">*</span></label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="sex" value="male" required {{ old('sex') == 'male' ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Male / Homme</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="sex" value="female" required {{ old('sex') == 'female' ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="text-sm text-gray-600">Female / Femme</span>
                    </label>
                </div>
                @error('sex') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Email (Automated) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 text-sm">
                        Generated automatically: <span class="font-mono">fn.ln@company.com</span>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400 font-medium">Syntax: firstname.lastname@company.com</p>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Specialty -->
            <div>
                <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Specialty <span class="text-red-500">*</span></label>
                <input type="text" name="specialty" id="specialty" required value="{{ old('specialty') }}" placeholder="Ex: Web Development, Data Science"
                       class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('specialty') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Department -->
            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                <select name="department_id" id="department_id" required
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="" disabled selected>Select a department...</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Photo -->
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo (Optional)</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                       class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                @error('photo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="mt-8 flex items-center gap-4">
            <a href="{{ route('admin.trainers.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-sm">
                Create Trainer
            </button>
        </div>
    </form>
</div>

@endsection
