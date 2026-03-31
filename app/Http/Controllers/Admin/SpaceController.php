<?php
// app/Http/Controllers/Admin/SpaceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SpaceController extends Controller
{
    public function index(): View
    {
        $spaces = Space::with('department')
            ->withCount('hotspots')
            ->latest()
            ->paginate(10);

        return view('admin.spaces.index', compact('spaces'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.spaces.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:spaces,name',
            'description'   => 'nullable|string|max:1000',
            'department_id' => 'nullable|exists:departments,id',
            'photo_360'     => 'required|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        [$photo360Path, $thumbnailPath] = $this->handleUpload($request);

        Space::create([
            'name'           => $request->name,
            'description'    => $request->description,
            'department_id'  => $request->department_id,
            'photo_360_path' => $photo360Path,
            'thumbnail_path' => $thumbnailPath,
        ]);

        return redirect()->route('admin.spaces.index')
            ->with('success', 'Space created successfully.');
    }

    public function show(Space $space): View
    {
        $space->load('department', 'hotspots.employee', 'hotspots.targetScene');
        return view('admin.spaces.show', compact('space'));
    }

    public function edit(Space $space): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.spaces.edit', compact('space', 'departments'));
    }

    public function update(Request $request, Space $space): RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:spaces,name,' . $space->id,
            'description'   => 'nullable|string|max:1000',
            'department_id' => 'nullable|exists:departments,id',
            'photo_360'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        $data = $request->only('name', 'description', 'department_id');

        if ($request->hasFile('photo_360')) {
            // Delete old files
            if ($space->photo_360_path) {
                Storage::disk('public')->delete($space->photo_360_path);
            }
            if ($space->thumbnail_path) {
                Storage::disk('public')->delete($space->thumbnail_path);
            }

            [$photo360Path, $thumbnailPath] = $this->handleUpload($request);
            $data['photo_360_path'] = $photo360Path;
            $data['thumbnail_path'] = $thumbnailPath;
        }

        $space->update($data);

        return redirect()->route('admin.spaces.index')
            ->with('success', 'Space updated successfully.');
    }

    public function destroy(Space $space): RedirectResponse
    {
        if ($space->photo_360_path) {
            Storage::disk('public')->delete($space->photo_360_path);
        }
        if ($space->thumbnail_path) {
            Storage::disk('public')->delete($space->thumbnail_path);
        }

        $space->delete();

        return redirect()->route('admin.spaces.index')
            ->with('success', 'Space deleted successfully.');
    }

    /* -------------------------------------------------- */

    private function handleUpload(Request $request): array
    {
        $file   = $request->file('photo_360');
        $folder = 'spaces';

        Storage::disk('public')->makeDirectory($folder);
        Storage::disk('public')->makeDirectory($folder . '/thumbnails');

        // Store original 360° photo
        $filename     = uniqid('space_') . '.' . $file->getClientOriginalExtension();
        $photo360Path = $folder . '/' . $filename;
        Storage::disk('public')->put($photo360Path, file_get_contents($file));

        // Generate thumbnail with Intervention Image v3
        $manager   = new ImageManager(new Driver());
        $image     = $manager->read($file->getRealPath());

        // Crop center square then resize to thumbnail
        $width  = $image->width();
        $height = $image->height();
        $size   = min($width, $height);

        $image->crop($size, $size, (int)(($width - $size) / 2), 0)
              ->scale(width: 400);

        $thumbFilename  = 'thumb_' . $filename;
        $thumbnailPath  = $folder . '/thumbnails/' . $thumbFilename;
        $thumbFullPath  = storage_path('app/public/' . $thumbnailPath);

        $image->save($thumbFullPath);

        return [$photo360Path, $thumbnailPath];
    }
}