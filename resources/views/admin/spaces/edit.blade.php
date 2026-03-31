{{-- resources/views/admin/spaces/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Space')
@section('page-title', 'Edit Space')

@section('content')

<div class="max-w-2xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
<form method="POST" action="{{ route('admin.spaces.update', $space) }}"
      enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PUT')

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Space Name <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $space->name) }}"
               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none
                      focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"/>
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description
        </label>
        <textarea id="description" name="description" rows="3"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                         focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description', $space->description) }}</textarea>
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
                    {{ old('department_id', $space->department_id) == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Current photo --}}
    @if ($space->photo_360_path)
    <div class="p-4 bg-gray-50 rounded-xl">
        <p class="text-xs font-medium text-gray-600 mb-2">Current 360° Photo</p>
        <div class="flex items-center gap-4">
            @if ($space->thumbnail_path)
                <img src="{{ asset('storage/'.$space->thumbnail_path) }}"
                     class="w-24 h-16 object-cover rounded-lg" alt=""/>
            @endif
            <div class="text-xs text-gray-400">
                <p>{{ basename($space->photo_360_path) }}</p>
                <p class="mt-0.5">Upload a new file below to replace it</p>
            </div>
        </div>
    </div>
    @endif

    {{-- New photo upload --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Replace 360° Photo
            <span class="text-gray-400 font-normal">(optional)</span>
        </label>
        <div id="drop-zone"
             class="relative border-2 border-dashed border-gray-300 rounded-xl p-6
                    text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition">
            <input type="file" name="photo_360" id="photo_360" accept="image/*"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"/>
            <div id="drop-placeholder">
                <p class="text-sm text-gray-500">
                    <span class="text-indigo-600 font-medium">Click to upload</span>
                    or drag and drop
                </p>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 20MB</p>
            </div>
            <div id="drop-preview" class="hidden">
                <img id="preview-img" class="max-h-32 mx-auto rounded-lg object-contain" alt=""/>
                <p id="preview-name" class="text-xs text-gray-500 mt-2"></p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                   px-6 py-2.5 rounded-xl transition shadow-sm">
            Save Changes
        </button>
        <a href="{{ route('admin.spaces.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
    </div>

</form>
</div>
</div>

<script>
document.getElementById('photo_360').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
        document.getElementById('drop-placeholder').classList.add('hidden');
        document.getElementById('drop-preview').classList.remove('hidden');
        document.getElementById('preview-img').src = ev.target.result;
        document.getElementById('preview-name').textContent = file.name;
    };
    reader.readAsDataURL(file);
});
</script>

@endsection