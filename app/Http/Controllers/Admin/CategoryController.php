<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $request->validate([
            'title'             => 'required|string|max:255',
            'created_by'        => 'required|string|max:255',
            'stock'             => 'required|integer',
            'tag_id'            => 'nullable|string|max:50',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keywords'     => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        $category                   = new Category();
        $category->title            = $request->title;
        $category->created_by       = $request->created_by;
        $category->stock            = $request->stock;
        $category->tag_id           = $request->tag_id;
        $category->description      = $request->description;
        $category->meta_title       = $request->meta_title;
        $category->meta_keywords    = $request->meta_keywords;
        $category->meta_description = $request->meta_description;

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('backend/uploads/categories'), $filename);
            $category->image = 'backend/uploads/categories/' . $filename;
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

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

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title'             => 'required|string',
            'created_by'        => 'required|string',
            'stock'             => 'required|numeric',
            'tag_id'            => 'nullable|string',
            'description'       => 'nullable|string',
            'meta_title'        => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'image'             => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'created_by', 'stock', 'tag_id', 'description',
            'meta_title', 'meta_keywords', 'meta_description'
        ]);

        // Image handle
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('backend/uploads/categories/'), $imageName);
            $data['image'] = 'backend/uploads/categories/' . $imageName;
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Delete image if exists
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

}
