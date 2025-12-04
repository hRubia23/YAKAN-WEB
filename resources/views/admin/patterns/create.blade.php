@extends('layouts.admin')

@section('title', 'Create Pattern')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-maroon-700 to-maroon-800 shadow-2xl" style="background: linear-gradient(to right, #800000, #600000);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center">
                <a href="{{ route('admin.patterns.index') }}" class="text-maroon-100 hover:text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-white">Create New Pattern</h1>
                    <p class="text-maroon-100 mt-2">Add a new Yakan cultural pattern to the archive</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <form method="POST" action="{{ route('admin.patterns.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-maroon-600 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-maroon-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-maroon-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Pattern Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:border-maroon-500 transition-all duration-200 group-hover:border-gray-300" 
                               placeholder="Enter pattern name..." />
                        @error('name') 
                            <span class="text-red-500 text-xs mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span> 
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500">
                            <option value="">Select Category</option>
                            <option value="traditional" {{ old('category') == 'traditional' ? 'selected' : '' }}>Traditional</option>
                            <option value="modern" {{ old('category') == 'modern' ? 'selected' : '' }}>Modern</option>
                            <option value="contemporary" {{ old('category') == 'contemporary' ? 'selected' : '' }}>Contemporary</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level *</label>
                        <select name="difficulty_level" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500">
                            <option value="">Select Difficulty</option>
                            <option value="simple" {{ old('difficulty_level') == 'simple' ? 'selected' : '' }}>Simple</option>
                            <option value="medium" {{ old('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="complex" {{ old('difficulty_level') == 'complex' ? 'selected' : '' }}>Complex</option>
                        </select>
                        @error('difficulty_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base Color</label>
                        <input type="text" name="base_color" value="{{ old('base_color') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500" />
                        @error('base_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Pricing & Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-black text-gray-900 mb-4">Pricing & Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base Price Multiplier</label>
                        <input type="number" step="0.01" min="0" max="10" name="base_price_multiplier" value="{{ old('base_price_multiplier') }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon-500" />
                        @error('base_price_multiplier') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="is_active" value="1" class="rounded" @checked(old('is_active', true)) />
                        <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-black text-gray-900 mb-4">Tags</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', []))) class="rounded mr-2" />
                            <span class="text-sm">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Media Upload -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Media Upload</h2>
                </div>
                <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-300 bg-gradient-to-br from-gray-50 to-white">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-gray-700 font-medium mb-2">Drag & drop your pattern images here</p>
                    <p class="text-sm text-gray-500 mb-4">or click to browse from your computer</p>
                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            PNG, JPG, WEBP
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            Max 5MB each
                        </span>
                    </div>
                    <input type="file" name="media[]" multiple accept="image/*" class="hidden" id="media-upload" />
                    <button type="button" onclick="document.getElementById('media-upload').click()" 
                            class="mt-6 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Choose Files
                    </button>
                </div>
                <div id="media-preview" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center p-6 bg-gray-50 rounded-xl border border-gray-200">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Tip:</span> Fill in all required fields marked with *
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('admin.patterns.index') }}" 
                       class="px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-10 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold text-lg rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center border-2 border-red-800">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="relative">
                            Create Pattern
                            <span class="absolute -top-1 -right-2 w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Enhanced media upload with drag & drop
const dropzone = document.getElementById('dropzone');
const mediaUpload = document.getElementById('media-upload');
const mediaPreview = document.getElementById('media-preview');

// Drag and drop functionality
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('border-blue-500', 'bg-blue-50');
});

dropzone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    handleFiles(files);
});

// File input change handler
mediaUpload.addEventListener('change', function(e) {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    mediaPreview.innerHTML = '';
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                        <img src="${e.target.result}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                            ${file.name}
                        </div>
                        <button type="button" onclick="this.closest('.relative').remove()" 
                                class="absolute top-2 left-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <input type="text" name="media_alt[${index}]" placeholder="Enter alt text for accessibility..." 
                           class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                `;
                mediaPreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Form validation feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Please fill in all required fields
            </div>
        `;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
});
</script>
@endsection
