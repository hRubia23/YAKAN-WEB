@extends('layouts.admin')
@section('title', 'Add Product')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
    <h2 class="text-2xl font-bold mb-6">Add Product</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 border border-red-200 bg-red-50 rounded">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Product Name -->
        <div>
            <label class="block font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600" required>
        </div>

        <!-- Category -->
        <div>
            <label class="block font-medium text-gray-700 mb-2">Category</label>
            <div class="flex gap-2">
                <select name="category_id" id="categorySelect"
                    class="border rounded px-3 py-2 flex-1 focus:outline-none focus:ring-2 focus:ring-red-600"
                    style="border-color: #800000;">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" onclick="toggleNewCategory()" 
                    class="px-4 py-2 text-white rounded hover:opacity-90 transition-colors whitespace-nowrap"
                    style="background-color: #800000;">
                    <i class="fas fa-plus mr-1"></i> New
                </button>
            </div>
            
            <!-- New Category Input (Hidden by default) -->
            <div id="newCategoryDiv" class="mt-3 hidden">
                <div class="p-4 border-2 rounded-lg" style="border-color: #800000; background-color: #fff5f5;">
                    <label class="block font-medium text-gray-700 mb-2">New Category Name</label>
                    <div class="flex gap-2">
                        <input type="text" id="newCategoryInput" placeholder="Enter category name"
                            class="border rounded px-3 py-2 flex-1 focus:outline-none focus:ring-2"
                            style="border-color: #800000;">
                        <button type="button" onclick="addNewCategory()" 
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                            <i class="fas fa-check"></i> Add
                        </button>
                        <button type="button" onclick="toggleNewCategory()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Available Categories -->
            <div class="mt-3">
                <p class="text-sm text-gray-600 mb-2">Available Categories:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white cursor-pointer hover:opacity-80 transition-opacity"
                              style="background-color: #800000;"
                              onclick="document.getElementById('categorySelect').value='{{ $category->id }}'">
                            {{ $category->name }}
                        </span>
                    @endforeach
                    @if($categories->isEmpty())
                        <span class="text-sm text-gray-500 italic">No categories yet. Create one above!</span>
                    @endif
                </div>
            </div>
        </div>

        <script>
        function toggleNewCategory() {
            const div = document.getElementById('newCategoryDiv');
            const input = document.getElementById('newCategoryInput');
            div.classList.toggle('hidden');
            if (!div.classList.contains('hidden')) {
                input.focus();
            } else {
                input.value = '';
            }
        }

        function addNewCategory() {
            const input = document.getElementById('newCategoryInput');
            const categoryName = input.value.trim();
            
            if (!categoryName) {
                alert('Please enter a category name');
                return;
            }

            // Send AJAX request to create category
            fetch('{{ route("admin.categories.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: categoryName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new option to select
                    const select = document.getElementById('categorySelect');
                    const option = new Option(data.category.name, data.category.id, true, true);
                    select.add(option);
                    
                    // Show success message
                    alert('Category "' + data.category.name + '" created successfully!');
                    
                    // Reset and hide form
                    input.value = '';
                    toggleNewCategory();
                    
                    // Reload page to update category pills
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create category'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to create category. Please try again.');
            });
        }

        // Allow Enter key to submit new category
        document.getElementById('newCategoryInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addNewCategory();
            }
        });
        </script>

        <!-- Price -->
        <div>
            <label class="block font-medium text-gray-700">Price (₱)</label>
            <input type="number" name="price" value="{{ old('price') }}"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600" step="0.01"
                placeholder="0.00" required>
        </div>

        <!-- Stock -->
        <div>
            <label class="block font-medium text-gray-700">Stock</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600" min="0"
                required>
        </div>

        <!-- Description -->
        <div>
            <label class="block font-medium text-gray-700">Description</label>
            <textarea name="description"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600">{{ old('description') }}</textarea>
        </div>

        <!-- Image -->
        <div>
            <label class="block font-medium text-gray-700">Image</label>
            <input type="file" name="image" accept="image/*"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600">
        </div>

        <!-- Status -->
        <div>
            <label class="block font-medium text-gray-700">Status</label>
            <select name="status"
                class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600" required>
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors duration-200">
            Create Product
        </button>
    </form>
</div>
@endsection
