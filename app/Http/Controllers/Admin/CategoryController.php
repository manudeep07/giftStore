<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $slug = $request->filled('slug')
            ? Str::slug($request->string('slug'))
            : Str::slug($request->string('name'));

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
        }

        Category::query()->create([
            'name' => $request->validated('name'),
            'slug' => $slug,
            'description' => $request->validated('description'),
            'sort_order' => $request->validated('sort_order') ?? 0,
            'image_path' => $path,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category published.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $slug = $request->filled('slug')
            ? Str::slug($request->string('slug'))
            : Str::slug($request->string('name'));

        $payload = [
            'name' => $request->validated('name'),
            'slug' => $slug,
            'description' => $request->validated('description'),
            'sort_order' => $request->validated('sort_order') ?? 0,
        ];

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($payload);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category removed.');
    }
}
