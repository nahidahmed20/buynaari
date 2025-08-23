<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Carbon\Traits\Creator;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Subcategories/Index', [
            'subcategories' => SubCategory::with('category')->orderByDesc('id')->get(),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Subcategories/Create', [
            'creators' => User::all(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|integer',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|array',  // array type
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Image handle
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('backend/uploads/subcategories', 'public');
        }

        // Ensure meta_keywords is saved as JSON string
        $validated['meta_keywords'] = isset($validated['meta_keywords']) ? json_encode($validated['meta_keywords']) : null;

        // Create subcategory
        SubCategory::create($validated);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Admin/Subcategories/Edit', [
            'categories' => Category::all(),
            'subcategory' => SubCategory::where('id', $id)->with('category')->first(),
            'creators' => User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|integer',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string', // Vue থেকে JSON string হিসেবে পাঠানো হচ্ছে
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Handle image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subcategory->image && Storage::disk('public')->exists($subcategory->image)) {
                Storage::disk('public')->delete($subcategory->image);
            }
            $validated['image'] = $request->file('image')->store('backend/uploads/subcategories', 'public');
        } else {
            $validated['image'] = $subcategory->image; // Preserve old image
        }

        // Update subcategory
        $subcategory->update($validated);

        return redirect()->route('subcategories.index')->with('success', 'Sub Category updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);

        // Check if subcategory has products
        if ($subcategory->products()->exists()) {
            return redirect()->route('subcategories.index')->with('error', 'Delete products first.');
        }

        // Delete image if exists
        if ($subcategory->image && Storage::disk('public')->exists($subcategory->image)) {
            Storage::disk('public')->delete($subcategory->image);
        }

        // Delete subcategory
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
