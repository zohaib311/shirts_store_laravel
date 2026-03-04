<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shirt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShirtController extends Controller
{
    public function insert(Request $request)
    {
        if ($request->hasFile('myfile')) {

            $file = $request->file('myfile');

            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('uploads', $filename, 'public');

            $shirt = new Shirt();
            $shirt->name = $request->name;
            $shirt->description = $request->description;
            $shirt->image = $path;
            $shirt->price = $request->price;
            $shirt->discount_price = $request->discount_price;
            $shirt->category = $request->category;
            $shirt->in_stock = $request->stock;
            $shirt->save();

            return back()->with('status', [
                'type' => 'success',
                'message' => 'Product added successfully.'
            ]);
        }
        return back()->with('status', [
            'type' => 'delete',
            'message' => 'File not uploaded.'
        ]);

        // return  print_r($request->only(['name', 'discount_price', "description", "category", "stock", 'myfile']));
    }


    /**
     * Get Function that will get shirts for both api and blade
     */
    private function getAllShirts()
    {
        return Shirt::all();
    }

    public function get()
    {
        $shirtsData = $this->getAllShirts();
        return view('admin.layout.pages.products', compact('shirtsData'));
    }



    /**
     * End Get Function
     */


    private function privateDelete($id)
    {
        $shirtsData = Shirt::findOrFail($id);
        return ($shirtsData);
    }

    public function delete($id)
    {
        $shirt = $this->privateDelete($id);
        if ($shirt->image && Storage::disk('public')->exists($shirt->image)) {
            Storage::disk('public')->delete($shirt->image);
        }

        $shirt->delete();
        return redirect()->route('products')->with('status', [
            'type' => 'delete',
            'message' => 'Product deleted successfully.'
        ]);;
    }



    public function edit($id)
    {
        $shirt = Shirt::findOrFail($id);
        return view('admin.layout.pages.edit', compact('shirt'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'myfile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $shirt = Shirt::findOrFail($id);

        // If new image uploaded
        if ($request->hasFile('myfile')) {

            // Delete old image
            if ($shirt->image && Storage::disk('public')->exists($shirt->image)) {
                Storage::disk('public')->delete($shirt->image);
            }

            // Upload new image
            $file = $request->file('myfile');
            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('uploads', $filename, 'public');

            $shirt->image = $path;
        }

        // Update other fields
        $shirt->name = $validated['name'];
        $shirt->description = $validated['description'] ?? null;
        $shirt->price = $validated['price'];
        $shirt->discount_price = $validated['discount_price'] ?? null;
        $shirt->category = $validated['category'];
        $shirt->in_stock = $validated['stock'];

        $shirt->save();

        return redirect()->route('products')->with('status', [
            'type' => 'success',
            'message' => 'Product updated successfully.'
        ]);
    }


    /** 
     * 
     * Controllers Functions for Apis
     * 
     */


    public function insertApi(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'myfile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('myfile');

        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('uploads', $filename, 'public');

        $shirt = Shirt::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $path,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'category' => $validated['category'],
            'in_stock' => $validated['stock']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $shirt
        ], 201);
    }

    public function getApi()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getAllShirts(),
        ]);
    }

    public function deleteApi($id)
    {
        $shirt = Shirt::findOrFail($id);

        if ($shirt->image && Storage::disk('public')->exists($shirt->image)) {
            Storage::disk('public')->delete($shirt->image);
        }

        $shirt->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }



    public function updateApi(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'myfile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $shirt = Shirt::findOrFail($id);

        if ($request->hasFile('myfile')) {

            if ($shirt->image && Storage::disk('public')->exists($shirt->image)) {
                Storage::disk('public')->delete($shirt->image);
            }

            $file = $request->file('myfile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');

            $shirt->image = $path;
        }

        $shirt->name = $validated['name'];
        $shirt->description = $validated['description'] ?? null;
        $shirt->price = $validated['price'];
        $shirt->discount_price = $validated['discount_price'] ?? null;
        $shirt->category = $validated['category'];
        $shirt->in_stock = $validated['stock'];

        $shirt->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $shirt
        ]);
    }
}
