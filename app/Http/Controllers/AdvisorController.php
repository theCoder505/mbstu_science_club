<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdvisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Advisor::orderBy('id', 'DESC');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('advisor_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('department', 'LIKE', "%{$search}%")
                    ->orWhere('designation', 'LIKE', "%{$search}%")
                    ->orWhere('club_role', 'LIKE', "%{$search}%");
            });
        }

        $advisors = $query->get();

        return Inertia::render('advisors/Index', [
            'advisors' => $advisors,
            'searchTerm' => $request->search ?? ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('advisors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'advisor_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:advisors,email'],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'club_role' => ['required', 'string', 'max:255', 'in:Advisor,Moderator'],

            'facebook' => ['nullable', 'string', 'max:255', 'url'],
            'twitter' => ['nullable', 'string', 'max:255', 'url'],
            'linkedin' => ['nullable', 'string', 'max:255', 'url'],
            'google_scholar' => ['nullable', 'string', 'max:255', 'url'],
            'research_gate' => ['nullable', 'string', 'max:255', 'url'],
            'portfolio' => ['nullable', 'string', 'max:255', 'url'],

            'password' => ['required', 'string', 'min:8', 'max:255'],

            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:20480'],
        ]);

        try {
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImageName = 'profile_' . time() . '.' . $profileImage->getClientOriginalExtension();
                $profileImagePath = $profileImage->storeAs('advisors', $profileImageName, 'public');
                $validated['profile_image'] = $profileImagePath;
            }

            if ($request->hasFile('signature')) {
                $signature = $request->file('signature');
                $signatureName = 'signature_' . time() . '.' . $signature->getClientOriginalExtension();
                $signaturePath = $signature->storeAs('advisor_sign', $signatureName, 'public');
                $validated['signature'] = $signaturePath;
            }

            // Hash password
            $validated['password'] = Hash::make($validated['password']);

            // Create advisor
            Advisor::create($validated);

            $advisors = Advisor::orderBy('id', 'DESC')->get();
            return Inertia::render('advisors/Index', [
                'advisors' => $advisors,
                'searchTerm' => $request->search ?? ''
            ]);
        } catch (\Exception $e) {
            // Clean up uploaded files if error occurs
            if (isset($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }
            if (isset($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }

            return back()->with('error', 'Error creating advisor: ' . $e->getMessage());
        }
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Laravel will inject based on route parameter
    {
        $advisor = Advisor::findOrFail($id);
        return Inertia::render('advisors/Edit', [
            'advisor' => $advisor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request['advisor_id'];
        $advisor = Advisor::findOrFail($id);

        $validated = $request->validate([
            'advisor_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:advisors,email,' . $advisor->id],
            'department' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'club_role' => ['required', 'string', 'max:255', 'in:Advisor,Moderator'],

            'facebook' => ['nullable', 'string', 'max:255', 'url'],
            'twitter' => ['nullable', 'string', 'max:255', 'url'],
            'linkedin' => ['nullable', 'string', 'max:255', 'url'],
            'google_scholar' => ['nullable', 'string', 'max:255', 'url'],
            'research_gate' => ['nullable', 'string', 'max:255', 'url'],
            'portfolio' => ['nullable', 'string', 'max:255', 'url'],

            'password' => ['nullable', 'string', 'min:8', 'max:255'],

            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:20480'],
            'current_profile_image' => ['nullable', 'string'],
            'current_signature' => ['nullable', 'string'],
        ]);

        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old profile image if exists
                if ($advisor->profile_image) {
                    Storage::disk('public')->delete($advisor->profile_image);
                }

                $profileImage = $request->file('profile_image');
                $profileImageName = 'profile_' . time() . '.' . $profileImage->getClientOriginalExtension();
                $profileImagePath = $profileImage->storeAs('advisors', $profileImageName, 'public');
                $validated['profile_image'] = $profileImagePath;
            } else {
                // Keep the current profile image if not updating
                $validated['profile_image'] = $request->input('current_profile_image', $advisor->profile_image);
            }

            // Handle signature upload
            if ($request->hasFile('signature')) {
                // Delete old signature if exists
                if ($advisor->signature) {
                    Storage::disk('public')->delete($advisor->signature);
                }

                $signature = $request->file('signature');
                $signatureName = 'signature_' . time() . '.' . $signature->getClientOriginalExtension();
                $signaturePath = $signature->storeAs('advisor_sign', $signatureName, 'public');
                $validated['signature'] = $signaturePath;
            } else {
                // Keep the current signature if not updating
                $validated['signature'] = $request->input('current_signature', $advisor->signature);
            }

            // Hash password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Remove temporary fields before updating
            unset($validated['current_profile_image']);
            unset($validated['current_signature']);

            // Update advisor
            $advisor->update($validated);

            return redirect()->route('advisors.index')
                ->with('success', 'Advisor updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating advisor: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $advisor = Advisor::find($id);
        try {
            // Delete associated files
            if ($advisor->profile_image) {
                Storage::disk('public')->delete($advisor->profile_image);
            }
            if ($advisor->signature) {
                Storage::disk('public')->delete($advisor->signature);
            }

            $advisor->delete();

            return redirect()->route('advisors-and-moderators.index')
                ->with('success', 'Advisor deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting advisor: ' . $e->getMessage());
        }
    }
}
