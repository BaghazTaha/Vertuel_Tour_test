{{-- resources/views/admin/employees/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'New Employee')
@section('page-title', 'New Employee')

@section('content')

<div class="max-w-2xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
<form method="POST" action="{{ route('admin.employees.store') }}"
      enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- Photo upload --}}
    <div class="flex items-center gap-5">
        <div id="photo-preview"
             class="w-20 h-20 rounded-full bg-indigo-100 text-indigo-400 flex items-center
                    justify-center text-3xl font-bold shrink-0 overflow-hidden border-2 border-dashed
                    border-indigo-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
            <input type="file" name="photo" id="photo" accept="image/*"
                   class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4
                          file:rounded-lg file:border-0 file:text-xs file:font-medium
                          file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2MB</p>
            @error('photo')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Name row --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                First Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="first_name" name="first_name"
                   value="{{ old('first_name') }}" placeholder="Youssef"
                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                          focus:ring-2 focus:ring-indigo-500
                          {{ $errors->has('first_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
            @error('first_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="last_name" name="last_name"
                   value="{{ old('last_name') }}" placeholder="El Amrani"
                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                          focus:ring-2 focus:ring-indigo-500
                          {{ $errors->has('last_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
            @error('last_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Matricule + Job title --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="matricule" class="block text-sm font-medium text-gray-700 mb-1">
                Matricule <span class="text-red-500">*</span>
            </label>
            <input type="text" id="matricule" name="matricule"
                   value="{{ old('matricule') }}" placeholder="EMP-001"
                   class="w-full px-4 py-2.5 border rounded-xl text-sm font-mono
                          focus:outline-none focus:ring-2 focus:ring-indigo-500
                          {{ $errors->has('matricule') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
            @error('matricule')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">
                Job Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="job_title" name="job_title"
                   value="{{ old('job_title') }}" placeholder="Software Engineer"
                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                          focus:ring-2 focus:ring-indigo-500
                          {{ $errors->has('job_title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
            @error('job_title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Department --}}
    <div>
        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
            Department
        </label>
        <select id="department_id" name="department_id"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
            <option value="">— No department —</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Email + Phone --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}" placeholder="name@company.com"
                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                          focus:ring-2 focus:ring-indigo-500
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
            @error('email')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" id="phone" name="phone"
                   value="{{ old('phone') }}" placeholder="+212 600-000000"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                          focus:outline-none focus:ring-2 focus:ring-indigo-500"/>
            @error('phone')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Note --}}
    <p class="text-xs text-gray-400 bg-gray-50 rounded-xl px-4 py-3">
        A login account will be automatically created for this employee
        using the email above with default password <strong>password</strong>.
        They can change it after first login.
    </p>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                   px-6 py-2.5 rounded-xl transition shadow-sm">
            Create Employee
        </button>
        <a href="{{ route('admin.employees.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
    </div>

</form>
</div>
</div>

{{-- Photo preview script --}}
<script>
document.getElementById('photo').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = `<img src="${ev.target.result}"
            class="w-full h-full object-cover" />`;
    };
    reader.readAsDataURL(file);
});
</script>

@endsection