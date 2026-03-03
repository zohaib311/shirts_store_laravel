@extends('admin.layout.adminLayout')

@section('title')
    <title>Products - Admin Panel</title>
@endsection()

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Products</h2>

        {{-- Show allert in different colors. --}}
        @if (session('status'))
            @php
                $type = session('status.type');
                $message = session('status.message');

                $bg = $type === 'success' ? 'bg-green-500' : 'bg-black';
            @endphp

            <div id="status" class="fixed top-6 right-6 z-50 transform translate-x-full opacity-0 transition duration-300">

                <div class="{{ $bg }} text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3">

                    <!-- Icon -->
                    <span class="text-lg">
                        {{ $type === 'success' ? '✔' : '🗑' }}
                    </span>

                    <!-- Message -->
                    <span class="font-medium">
                        {{ $message }}
                    </span>

                </div>
            </div>
            <script>
                const status = document.getElementById('status');

                // Slide In
                setTimeout(() => {
                    status.classList.remove('translate-x-full', 'opacity-0');
                    status.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                // Auto Hide After 3 Seconds
                setTimeout(() => {
                    status.classList.add('translate-x-full', 'opacity-0');
                }, 3000);
            </script>
        @endif

        <button data-modal="productModal"
            class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
            + Add Product
        </button>
    </div>

    <!-- Search + Filter -->
    <div class="bg-white p-4 rounded-xl shadow mb-6 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
        <input type="text" placeholder="Search products..."
            class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">

        <select class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>All Categories</option>
            <option>Casual</option>
            <option>Formal</option>
            <option>New Arrival</option>
        </select>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left">

            <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                <tr>
                    <th class="px-2 py-4">id</th>
                    <th class="px-3 py-4">Image</th>
                    <th class="px-3 py-4">Name</th>
                    <th class="px-2 py-4">Description</th>
                    <th class="px-3 py-4">Price</th>
                    <th class="px-2 py-4">Discounted Price</th>
                    <th class="px-4 py-4">Category</th>
                    <th class="px-4 py-4">Stock</th>
                    <th class="px-4 py-4">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach ($shirtsData as $shirt)
                    <tr class="hover:bg-gray-50">

                        <td class="px-3 py-4">
                            {{ $shirt->id }}
                        </td>

                        <td class="px-3 py-4">
                            <img src="{{ asset('storage/' . $shirt->image) }}" class="w-22 h-18 object-cover rounded">
                        </td>
                        <td class="px-2 py-4 font-medium">
                            {{ $shirt->name }}
                        </td>


                        <td class="px-4 py-4 max-w-[150px] truncate" title="{{ $shirt->description }} ">
                            {{ $shirt->description }}
                        </td>


                        <td class="px-3 py-4">
                            {{ $shirt->price }}

                        </td>

                        <td class="px-4 py-4">
                            {{ $shirt->discount_price }}
                        </td>

                        <td class="px-3 py-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                {{ $shirt->category }}

                            </span>
                        </td>

                        <td class="px-2 py-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                {{ $shirt->in_stock }}

                            </span>
                        </td>

                        <td class="px-6 py-6 flex flex-wrap gap-[5px]">
                            <a href="{{ route('product.edit', $shirt->id) }}" wire:navigate
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                                Update
                            </a>
                            <a href="{{ route('product.delete', $shirt->id) }}"
                                class="px-3  py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                                Delete
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

        <!-- ADD PRODUCT MODAL -->
        <div id="productModal" class="modal fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50">

            <!-- Modal Wrapper -->
            <div class="flex items-end sm:items-center justify-center min-h-screen p-4">

                <!-- Modal Box -->
                <div
                    class="modal-content bg-white w-full max-w-3xl rounded-t-2xl sm:rounded-2xl shadow-2xl
                    transform transition-all duration-300 scale-95 opacity-0">

                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b sticky top-0 bg-white rounded-t-2xl">
                        <h2 class="text-xl sm:text-2xl font-semibold">
                            Add New Product
                        </h2>

                        <button data-close class="text-gray-400 hover:text-red-500 text-2xl leading-none">
                            &times;
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">

                        <form id="createProductForm" action="/product/item/insert" enctype="multipart/form-data"
                            method="post" class="space-y-6">
                            @csrf
                            <!-- Grid Fields -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-medium mb-1">Product Name</label>
                                    <input type="text"
                                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        name="name">

                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Category</label>
                                    <select
                                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        name="category">
                                        <option>Casual</option>
                                        <option>Formal</option>
                                        <option>New Arrival</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Price</label>
                                    <input type="number"
                                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        name="price">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Discounted Price</label>
                                    <input type="number"
                                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        name="discount_price">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Stock</label>
                                    <input type="number"
                                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        name="stock">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Image</label>
                                    <input type="file" name="myfile"
                                        class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-xl file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100
                                                border rounded-xl cursor-pointer focus:outline-none">

                                </div>


                            </div>

                            <!-- Image Upload -->
                            {{-- <div>
                                <label class="block text-sm font-medium mb-2">Product Image</label>

                                <div
                                    class="border-2 border-dashed rounded-xl p-6 text-center hover:border-indigo-500 transition cursor-pointer">
                                    <input type="file" class="hidden" id="imageInput">
                                    <label for="imageInput" class="cursor-pointer text-gray-500">
                                        📁 Click to upload or drag & drop
                                    </label>
                                </div>
                            </div> --}}

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Description</label>
                                <textarea rows="4"
                                    class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" name="description"></textarea>
                            </div>

                        </form>

                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-2xl">

                        <button type="button" data-close
                            class="w-full sm:w-auto px-5 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                            Cancel
                        </button>

                        <button type="submit" form="createProductForm"
                            class="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                            Save Product
                        </button>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection


@section('scripts')
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            const content = modal.querySelector('.modal-content');
            if (!content) return;

            modal.classList.remove('hidden');

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(modal) {
            if (!modal) return;
            const content = modal.querySelector('.modal-content');
            if (!content) return;

            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Open buttons
        document.querySelectorAll('[data-modal]').forEach(button => {
            button.addEventListener('click', function() {
                openModal(this.dataset.modal);
            });
        });

        // Close buttons
        document.querySelectorAll('[data-close]').forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                closeModal(modal);
            });
        });

        // Click outside close
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal(modal);
                }
            });
        });

        // ESC close
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                document.querySelectorAll('.modal:not(.hidden)').forEach(modal => {
                    closeModal(modal);
                });
            }
        });
    </script>
@endsection
