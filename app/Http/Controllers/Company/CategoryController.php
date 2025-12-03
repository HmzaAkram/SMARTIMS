<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index($tenant)
{
    $categories = Category::withCount('items')->paginate(10);
    return view('company.categories.index', compact('categories'));
}

    public function create($tenant)
    {
        return view('company.categories.create');
    }

    public function store(Request $request, $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('company.categories.index', $tenant)
            ->with('success', 'Category created successfully!');
    }

    public function edit($tenant, Category $category)
    {
        return view('company.categories.edit', compact('category'));
    }

    public function update(Request $request, $tenant, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('company.categories.index', $tenant)
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($tenant, Category $category)
    {
        $category->delete();
        return redirect()->route('company.categories.index', $tenant)
            ->with('success', 'Category deleted successfully!');
    }
}