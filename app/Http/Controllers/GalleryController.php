<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery items.
     */
    public function index()
    {
        $gallery = Gallery::orderBy('position', 'ASC')->get();
        
        return Inertia::render('gallery/Index', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * Store newly created gallery items (supports multiple images).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|max:20480', // 20MB max per image
        ]);

        // Get the current max position
        $maxPosition = Gallery::max('position') ?? 0;

        $uploadedCount = 0;

        // Process each uploaded image
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = 'gallery_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('gallery', $imageName, 'public');

                // Create gallery item with auto-incremented position and default "show" status
                Gallery::create([
                    'image_path' => $imagePath,
                    'status' => 'show', // Default to show
                    'position' => ++$maxPosition,
                ]);

                $uploadedCount++;
            }
        }

        return redirect()->route('gallery.index')->with('success', "Added Successfully!");
    }

    /**
     * Update the specified gallery item (supports image replacement).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:galleries,id',
            'image_path' => 'nullable|string|max:500',
            'new_image' => 'nullable|image|max:20480', // 20MB max
            'status' => 'required|in:show,hide',
            'position' => 'nullable|integer|min:1',
        ]);

        $gallery = Gallery::findOrFail($validated['id']);

        // Prepare update data
        $updateData = [
            'status' => $validated['status'],
            'position' => $validated['position'] ?? $gallery->position,
        ];

        // Handle image replacement if new image is uploaded
        if ($request->hasFile('new_image')) {
            // Delete old image from storage
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            // Upload new image
            $newImage = $request->file('new_image');
            $imageName = 'gallery_' . time() . '_' . uniqid() . '.' . $newImage->getClientOriginalExtension();
            $imagePath = $newImage->storeAs('gallery', $imageName, 'public');

            // Add new image path to update data
            $updateData['image_path'] = $imagePath;
        }

        // Update the gallery item
        $gallery->update($updateData);

        return redirect()->route('gallery.index')->with('success', 'Gallery item updated successfully!');
    }

    /**
     * Update positions for multiple gallery items (for drag and drop).
     */
    public function updatePositions(Request $request)
    {
        $validated = $request->validate([
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:galleries,id',
            'positions.*.position' => 'required|integer|min:1',
        ]);

        foreach ($validated['positions'] as $item) {
            Gallery::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return redirect()->route('gallery.index')->with('success', 'Positions updated successfully!');
    }

    /**
     * Remove the specified gallery item.
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete the image file from storage
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('gallery.index')->with('success', 'Gallery item deleted successfully!');
    }
}