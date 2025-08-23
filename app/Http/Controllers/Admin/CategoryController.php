<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cateogries = Category::orderBy('id', 'desc')->get();
        return Inertia::render('Admin/Category/Index', [
            'categories' => $cateogries,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Category/Create', [
            'creators' => User::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'created_by'       => 'required|string|max:255',
            'stock'            => 'required|integer',
            'tag_id'           => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable', // allow array
            'meta_description' => 'nullable|string|max:500',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('backend/uploads/categories', 'public');
        }

        if (isset($validated['meta_keywords']) && is_array($validated['meta_keywords'])) {
            $validated['meta_keywords'] = json_encode($validated['meta_keywords']);
        }

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Admin/Category/Edit', [
            'category' => Category::find($id),
            'creators' => User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        // Fetch category
        $category = Category::findOrFail($id);

        // Validate
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|numeric',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable', // can be JSON or array
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Handle meta_keywords
        if (isset($validated['meta_keywords'])) {
            if (is_array($validated['meta_keywords'])) {
                $validated['meta_keywords'] = json_encode($validated['meta_keywords']);
            }
        } else {
            $validated['meta_keywords'] = $category->meta_keywords; // keep old if not provided
        }

        // Handle image
        if ($request->file('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            // Store new image
            $validated['image'] = $request->file('image')->store('backend/uploads/categories', 'public');
        } else {
            $validated['image'] = $category->image; // keep old image if no new upload
        }

        // Update category
        $category->update($validated);


        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category has subcategories
        if ($category->subCategories()->exists()) {
            return redirect()->route('categories.index')->with('error', 'Delete subcategories first.');
        }

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete category
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
