<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $teammates = Team::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('teammate_name', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('designation', 'like', '%' . $search . '%')
                    ->orWhere('membership_period', 'like', '%' . $search . '%')
                    ->orWhere('small_desc', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('team/Index', [
            'teammates' => $teammates,
            'searchTerm' => $search, // Pass search term back to Vue for two-way binding
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('team/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teammate_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'teammate_name' => ['required', 'max:255'],
            'department' => ['required', 'max:255'],
            'designation' => ['required', 'max:255'],
            'membership_period' => ['required', 'max:255'],
            'small_desc' => ['required', 'max:255'],
            'details' => ['required'],
            'facebook_link' => ['required', 'url', 'max:255'],
            'linkedin_link' => ['required', 'url', 'max:255'],
        ]);

        if ($request->hasFile('teammate_image')) {
            $teammate_profile_img = $request->file('teammate_image');
            $teammate_profile_imgName = 'profile_img_' . time() . '.' . $teammate_profile_img->getClientOriginalExtension();
            $teammate_profile_imgPath = $teammate_profile_img->storeAs('teammate', $teammate_profile_imgName, 'public');
            $validated['teammate_image'] = $teammate_profile_imgPath;
        }

        Team::create($validated);

        return redirect()->route('team.index')->with('success', 'Teammate Added Successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teammate = Team::findOrFail($id);
        return Inertia::render('team/Edit', [
            'teammate' => $teammate
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request['id'];
        $teammate = Team::findOrFail($id);

        $validated = $request->validate([
            'teammate_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'teammate_name' => ['required', 'max:255'],
            'department' => ['required', 'max:255'],
            'designation' => ['required', 'max:255'],
            'membership_period' => ['required', 'max:255'],
            'small_desc' => ['required', 'max:255'],
            'details' => ['required'],
            'facebook_link' => ['required', 'url', 'max:255'], 
            'linkedin_link' => ['required', 'url', 'max:255'], 
        ]);


        if ($request->hasFile('teammate_image')) {
            if ($teammate->teammate_image) {
                Storage::disk('public')->delete($teammate->teammate_image);
            }

            $teammate_profile_img = $request->file('teammate_image');
            $teammate_profile_imgName = 'profile_img_' . time() . '.' . $teammate_profile_img->getClientOriginalExtension();
            $teammate_profile_imgPath = $teammate_profile_img->storeAs('teammate', $teammate_profile_imgName, 'public');
            $validated['teammate_image'] = $teammate_profile_imgPath;
        } else {
            unset($validated['teammate_image']);
        }

        $teammate->update($validated);
        $teammates = Team::orderBy('id', 'DESC')->get();
        return Inertia::render('team/Index', [
            'teammates' => $teammates
        ])->with('success', 'Teammate Details Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request['id'];
        $teammate = Team::findOrFail($id);
        $teammate->delete();
        $teammates = Team::orderBy('id', 'DESC')->get();
        return Inertia::render('team/Index', [
            'teammates' => $teammates
        ])->with('ssuccess', 'Teammate Removed Succesfully!');
    }
}
