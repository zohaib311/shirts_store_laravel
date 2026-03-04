@extends('admin.layout.adminLayout')

@section('title')
    <title>Edit Product - Admin Panel</title>
@endsection

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Update Product</h2>
            <p class="text-sm text-slate-500">Edit product details and save changes.</p>
        </div>

        <a href="{{ route('products') }}" wire:navigate
            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Back to Products
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-700 px-6 py-5 text-white">
            <h3 class="text-lg font-semibold">Product #{{ $shirt->id }}</h3>
            <p class="text-sm text-cyan-100">Keep your catalog details accurate and clear.</p>
        </div>

        <form action="{{ route('product.update', $shirt->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $shirt->name) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select name="category"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="Casual" {{ old('category', $shirt->category) == 'Casual' ? 'selected' : '' }}>Casual
                        </option>
                        <option value="Formal" {{ old('category', $shirt->category) == 'Formal' ? 'selected' : '' }}>Formal
                        </option>
                        <option value="New Arrival"
                            {{ old('category', $shirt->category) == 'New Arrival' ? 'selected' : '' }}>
                            New Arrival
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Price</label>
                    <input type="number" name="price" min="0" step="0.01"
                        value="{{ old('price', $shirt->price) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Discount Price</label>
                    <input type="number" name="discount_price" min="0" step="0.01"
                        value="{{ old('discount_price', $shirt->discount_price) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Stock</label>
                    <input type="number" name="stock" min="0" value="{{ old('stock', $shirt->in_stock) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required>
                </div>

                <div class="flex justify-content-space-between mx-4">
                    <img class="w-26 mr-4 h-20 object-cover rounded" src="{{ asset('storage/' . $shirt->image) }}"
                        alt="">
                    <input type="file" name="myfile"
                        class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-xl file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100
                                                border rounded-xl cursor-pointer focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description" rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $shirt->description) }}</textarea>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                <a href="{{ route('products') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200">
                    Cancel
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
