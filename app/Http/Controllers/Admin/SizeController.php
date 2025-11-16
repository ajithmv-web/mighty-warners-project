<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::withCount('products')->paginate(15);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
        ]);

        $size = Size::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'size' => $size]);
        }

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
        ]);

        $size->update($validated);

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        if ($size->products()->count() > 0) {
            return redirect()->route('admin.sizes.index')
                ->with('error', 'Cannot delete size with associated products.');
        }

        $size->delete();

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}
