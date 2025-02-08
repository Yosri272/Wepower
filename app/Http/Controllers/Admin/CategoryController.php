<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Category status toggle
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusToggle(Category $category)
    {
        // Update the user status
        $category->update([
            'status' => ! $category->status,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }
}
