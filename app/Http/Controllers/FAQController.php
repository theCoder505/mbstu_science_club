<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FAQ::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                    ->orWhere('answer', 'LIKE', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('id', 'DESC')->get();

        return Inertia::render('faqs/Index', [
            'faqs' => $faqs,
            'searchTerm' => $request->search ?? ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
        ]);

        FAQ::create($validated);

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:f_a_q_s,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::findOrFail($validated['id']);
        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
        ]);

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully!');
    }
}
