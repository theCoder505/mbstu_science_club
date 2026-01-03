<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{




    public function create()
    {
        return Inertia::render('templates/Create');
    }





    public function index(Request $request)
    {
        $query = Template::orderBy('id', 'DESC');
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('template_name', 'LIKE', "%{$search}%");
            });
        }

        $templates = $query->get();
        return Inertia::render('templates/Index', [
            'templates' => $templates
        ]);
    }








    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_name' => ['required', 'string', 'max:255'],
            'template_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:20480'],
        ]);

        if ($request->hasFile('template_image')) {
            $template_image = $request->file('template_image');
            $template_imageName = 'template_image_' . time() . '.' . $template_image->getClientOriginalExtension();
            $template_imagePath = $template_image->storeAs('templates', $template_imageName, 'public');
            $validated['template_image'] = $template_imagePath;
        }

        Template::create($validated);
        return redirect()->route('templates.index')->with('success', 'Template Added Successfully!');
    }






    public function edit($id)
    {
        $template = Template::find($id);
        return response()->json([
            'template' => $template
        ]);
    }




    public function update(Request $request)
    {
        $id = $request['id'];
        $template = Template::find($id);
        $validated = $request->validate([
            'template_name' => ['required', 'string', 'max:255'],
            'template_image' => ['nullable'],
        ]);

        if ($request->hasFile('template_image')) {
            $template_image = $request->file('template_image');
            $template_imageName = 'template_image_' . time() . '.' . $template_image->getClientOriginalExtension();
            $template_imagePath = $template_image->storeAs('templates', $template_imageName, 'public');
            $validated['template_image'] = $template_imagePath;
        } else {
            $validated['template_image'] = $request->input('template_image', $template->template_image);
        }

        $template->update($validated);
        return redirect()->route('templates.index')->with('success', 'Template Updated Successfully!');
    }





    public function destroy($id)
    {
        $template = Template::find($id);
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Template Removed Successfully!');
    }

    // 
}
