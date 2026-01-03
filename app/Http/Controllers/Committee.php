<?php

namespace App\Http\Controllers;

use App\Models\Committee as ModelsCommittee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class Committee extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModelsCommittee::orderBy('committee_period', 'DESC');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('committee_name', 'LIKE', "%{$search}%")
                    ->orWhere('committee_period', 'LIKE', "%{$search}%");
            });
        }

        $committees = $query->get();
        return Inertia::render('committees/Index', [
            'committees' => $committees,
            'searchTerm' => $request->search ?? ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('committees/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'committee_name' => ['required', 'string', 'max:255'],
            'committee_period' => ['required', 'string', 'max:100'],
            'committee_images' => ['required', 'array', 'min:1'],
            'committee_images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
        ]);

        $uploadedImages = [];

        if ($request->hasFile('committee_images')) {
            foreach ($request->file('committee_images') as $image) {
                $imageName = 'committee_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('committee_images', $imageName, 'public');
                $uploadedImages[] = $imagePath;
            }
        }

        $validated['committee_images'] = $uploadedImages;

        ModelsCommittee::create($validated);

        return redirect()->route('committees.index')->with('success', 'New Committee Added Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $committee = ModelsCommittee::findOrFail($id);

        // Convert images to full URLs for frontend
        $committee->images = $committee->images;

        return Inertia::render('committees/Edit', [
            'committee' => $committee,
        ]);
    }


    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id' => ['required', 'exists:committees,id'],
            'committee_name' => ['required', 'string', 'max:255'],
            'committee_period' => ['required', 'string', 'max:100'],
            'committee_images' => ['nullable', 'array'],
            'committee_images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['string'],
            'removed_images' => ['nullable', 'array'],
            'removed_images.*' => ['string'],
        ]);

        $committee = ModelsCommittee::findOrFail($validated['id']);

        // Start with existing images from the request (these are the ones user wants to keep)
        $images = $validated['existing_images'] ?? [];

        // Remove images marked for deletion
        if (!empty($validated['removed_images'])) {
            foreach ($validated['removed_images'] as $removedImage) {
                // Delete file from storage
                if (Storage::disk('public')->exists($removedImage)) {
                    Storage::disk('public')->delete($removedImage);
                }
            }
        }

        // Add new uploaded images
        if ($request->hasFile('committee_images')) {
            foreach ($request->file('committee_images') as $image) {
                $imageName = 'committee_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('committee_images', $imageName, 'public');
                $images[] = $imagePath;
            }
        }

        // Update the committee
        $committee->update([
            'committee_name' => $validated['committee_name'],
            'committee_period' => $validated['committee_period'],
            'committee_images' => $images,
        ]);

        return redirect()->route('committees.index')->with('success', 'Committee Updated Successfully!');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request['id'];
        $committee = ModelsCommittee::findOrFail($id);

        // Delete all associated images
        if ($committee->committee_images) {
            foreach ($committee->committee_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $committee->delete();

        return redirect()->route('committees.index')->with('success', 'Committee Deleted Successfully!');
    }
}
