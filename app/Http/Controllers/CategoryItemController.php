<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:category_items|max:255',
        ]);

        $category = new CategoryItem;
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'option_value' => $category->id,
            'option_text' => $category->name,
        ]);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
