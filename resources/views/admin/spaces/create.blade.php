{{-- resources/views/admin/spaces/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'New Space')
@section('page-title', 'New Space')

@section('content')

<div class="max-w-2xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
<form method="POST" action="{{ route('admin.spaces.store') }}"
      enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Space Name <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               placeholder="e.g. Main Office Lobby"
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
                  placeholder="Brief description of this space..."
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm
                         focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description') }}</textarea>
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

    {{-- 360 Photo upload --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            360° Photo <span class="text-red-500">*</span>
        </label>

        {{-- Drop zone --}}
        <div id="drop-zone"
             class="relative border-2 border-dashed border-gray-300 rounded-xl p-8
                    text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50
                    transition group">
            <input type="file" name="photo_360" id="photo_360" accept="image/*"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"/>

            <div id="drop-placeholder">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3 group-hover:text-indigo-400 transition"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586
                           a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2
                           2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">
                    <span class="text-indigo-600 font-medium">Click to upload</span>
                    or drag and drop
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Equirectangular 360° image — JPG, PNG, WEBP — max 20MB
                </p>
            </div>

            {{-- Preview --}}
            <div id="drop-preview" class="hidden">
                <img id="preview-img" class="max-h-40 mx-auto rounded-lg object-contain" alt=""/>
                <p id="preview-name" class="text-xs text-gray-500 mt-2"></p>
            </div>
        </div>

        @error('photo_360')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                   px-6 py-2.5 rounded-xl transition shadow-sm">
            Create Space
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