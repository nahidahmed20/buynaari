<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::with(['category', 'subcategory'])->get();

        return Inertia::render('Admin/Product/Index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();

        return Inertia::render('Admin/Product/Create', compact('categories', 'subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:male,female,unisex',
            'thumbnail' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'tags' => 'nullable|array',
            'tag_number' => 'nullable|string|max:100',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|array',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048', // multiple product images
        ]);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('backend/uploads/products', 'public');
        }

        // Convert array fields to JSON
        foreach (['sizes', 'colors', 'tags', 'meta_keywords'] as $field) {
            $validated[$field] = json_encode($validated[$field] ?? []);
        }

        // Defaults
        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['price'] = $validated['price'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        // Save product
        $product = Product::create($validated);

        // Save multiple images in ProductImage model
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('backend/uploads/products', 'public');
                $product->images()->create([
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $product = Product::with('images', 'category', 'subcategory')->findOrFail($id);

    // Convert JSON fields back to array for Vue
    $product->sizes = json_decode($product->sizes ?? '[]');
    $product->colors = json_decode($product->colors ?? '[]');
    $product->tags = json_decode($product->tags ?? '[]');
    $product->meta_keywords = json_decode($product->meta_keywords ?? '[]');

    return Inertia::render('Admin/Product/Show', [
        'product' => $product,
        'categories' => Category::all(),
        'subCategories' => SubCategory::all(),
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $product = Product::findOrFail($id);

        $categories = Category::all();
        $subCategories = SubCategory::all();

        return Inertia::render('Admin/Product/Edit', compact('product', 'categories', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validation
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:male,female,unisex',
            'thumbnail' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'tags' => 'nullable|array',
            'tag_number' => 'nullable|string|max:100',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|array',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('backend/uploads/products', 'public');
        }

        // Convert array fields to JSON
        foreach (['sizes', 'colors', 'tags', 'meta_keywords'] as $field) {
            $validated[$field] = json_encode($validated[$field] ?? []);
        }

        // Defaults
        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['price'] = $validated['price'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        // Update product
        $product->update($validated);

        // Save new multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('backend/uploads/products', 'public');
                $product->images()->create([
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Delete thumbnail
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Delete all product images
        foreach ($product->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        // Delete product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

}
