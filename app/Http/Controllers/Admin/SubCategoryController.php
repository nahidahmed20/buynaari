<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Carbon\Traits\Creator;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        // dd($request->all());
        $request->validate([
            'title'             => 'required|string|max:255',
            'parent_category'   => 'required',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|integer',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('backend/uploads/subcategories'), $filename);
            $subCategoryImage = 'backend/uploads/subcategories/' . $filename;
        }

        SubCategory::create([
            'title'             => $request->title,
            'category_id'       => $request->parent_category,
            'created_by'        => $request->created_by,
            'stock'             => $request->stock,
            'tag_id'            => $request->tag_id,
            'description'       => $request->description,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'image'             => $subCategoryImage,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Sub Category created successfully.');
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
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'required',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|integer',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);


        $subCategoryImage = $subcategory->image; // default old image

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($subCategoryImage && file_exists(public_path($subCategoryImage))) {
                unlink(public_path($subCategoryImage));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/uploads/subcategories/'), $filename);
            $subCategoryImage = 'backend/uploads/subcategories/' . $filename;
        }

        $subcategory->update([
            'title'             => $request->title,
            'category_id'       => $request->category_id,
            'created_by'        => $request->created_by,
            'stock'             => $request->stock,
            'tag_id'            => $request->tag_id,
            'description'       => $request->description,
            'meta_title'        => $request->meta_title,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'image'             => $subCategoryImage,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Sub Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
