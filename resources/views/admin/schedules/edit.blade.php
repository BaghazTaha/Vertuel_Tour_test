@extends('layouts.admin')

@section('title', 'Edit Schedule')
@section('page-title', 'Edit Schedule Session')

@section('content')

<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject / Course Name <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" required value="{{ old('subject', $schedule->subject) }}"
                       class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('subject') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Space -->
                <div>
                    <label for="space_id" class="block text-sm font-medium text-gray-700 mb-1">Space (Room) <span class="text-red-500">*</span></label>
                    <select name="space_id" id="space_id" required
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($spaces as $space)
                            <option value="{{ $space->id }}" {{ old('space_id', $schedule->space_id) == $space->id ? 'selected' : '' }}>
                                {{ $space->name }} (Cap: {{ $space->capacity ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    @error('space_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                
                <!-- Trainer -->
                <div>
                    <label for="trainer_id" class="block text-sm font-medium text-gray-700 mb-1">Trainer <span class="text-red-500">*</span></label>
                    <select name="trainer_id" id="trainer_id" required
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('trainer_id', $schedule->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                {{ $trainer->first_name }} {{ $trainer->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('trainer_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                
                <!-- Group -->
                <div>
                    <label for="group_id" class="block text-sm font-medium text-gray-700 mb-1">Group <span class="text-red-500">*</span></label>
                    <select name="group_id" id="group_id" required
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id', $schedule->group_id) == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Day -->
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day of Week <span class="text-red-500">*</span></label>
                    <select name="day_of_week" id="day_of_week" required
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" required value="{{ old('start_time', $schedule->start_time) }}"
                           class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('start_time') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" id="end_time" required value="{{ old('end_time', $schedule->end_time) }}"
                           class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('end_time') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

        </div>

        <div class="mt-8 flex items-center gap-4">
            <a href="{{ route('admin.schedules.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-sm">
                Update Session
            </button>
        </div>
    </form>
</div>

@endsection
