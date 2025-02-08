<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\SubCategory;
use App\Repositories\SubCategoryRepository;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = auth()->user()->shop->subCategories()->latest('id')->paginate(10);

        return view('shop.sub-category.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = auth()->user()->shop->categories()->active()->get();

        return view('shop.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryRequest $request)
    {
        SubCategoryRepository::storeByRequest($request);

        return to_route('shop.subcategory.index')->with('success', __('Sub Category created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = auth()->user()->shop->categories()->active()->get();

        return view('shop.sub-category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubCategoryRequest $request, SubCategory $subCategory)
    {
        SubCategoryRepository::updateByRequest($request, $subCategory);

        return to_route('shop.subcategory.index')->with('success', __('Sub Category updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return to_route('shop.subcategory.index')->with('success', __('Deleted successfully'));
    }

    /**
     * status toggle
     */
    public function statusToggle(SubCategory $subCategory)
    {
        $subCategory->update(['is_active' => ! $subCategory->is_active]);

        return back()->with('success', __('Status updated successfully'));
    }
}
