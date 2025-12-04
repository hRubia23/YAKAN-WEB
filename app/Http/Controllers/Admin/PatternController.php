<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YakanPattern;
use App\Models\PatternTag;
use App\Models\PatternMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PatternController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = YakanPattern::with('media', 'tags');

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $patterns = $query->latest()->paginate(12);
        $tags = PatternTag::all();

        return view('admin.patterns.index', compact('patterns', 'tags'));
    }

    public function create()
    {
        $tags = PatternTag::all();
        return view('admin.patterns.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'required|in:simple,medium,complex',
            'base_color' => 'nullable|string|max:50',
            'color_variations' => 'nullable|array',
            'base_price_multiplier' => 'nullable|numeric|min:0|max:10',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:pattern_tags,id',
            'media' => 'nullable|array',
            'media.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'media_alt' => 'nullable|array',
            'media_alt.*' => 'nullable|string|max:255',
        ]);

        $pattern = YakanPattern::create($validated);

        if (!empty($validated['tags'])) {
            $pattern->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('patterns', 'uploads');
                $pattern->media()->create([
                    'type' => 'image',
                    'path' => $path,
                    'alt_text' => $validated['media_alt'][$index] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.patterns.index')->with('success', 'Pattern created successfully.');
    }

    public function show(YakanPattern $pattern)
    {
        $pattern->load('media', 'tags');
        return view('admin.patterns.show', compact('pattern'));
    }

    public function edit(YakanPattern $pattern)
    {
        $pattern->load('media', 'tags');
        $tags = PatternTag::all();
        return view('admin.patterns.edit', compact('pattern', 'tags'));
    }

    public function update(Request $request, YakanPattern $pattern)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'difficulty_level' => 'required|in:simple,medium,complex',
            'base_color' => 'nullable|string|max:50',
            'color_variations' => 'nullable|array',
            'base_price_multiplier' => 'nullable|numeric|min:0|max:10',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:pattern_tags,id',
            'media' => 'nullable|array',
            'media.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'media_alt' => 'nullable|array',
            'media_alt.*' => 'nullable|string|max:255',
        ]);

        $pattern->update($validated);
        $pattern->tags()->sync($validated['tags'] ?? []);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('patterns', 'uploads');
                $pattern->media()->create([
                    'type' => 'image',
                    'path' => $path,
                    'alt_text' => $validated['media_alt'][$index] ?? null,
                    'sort_order' => $pattern->media()->max('sort_order') + 1 + $index,
                ]);
            }
        }

        return redirect()->route('admin.patterns.index')->with('success', 'Pattern updated successfully.');
    }

    public function destroy(YakanPattern $pattern)
    {
        foreach ($pattern->media as $media) {
            Storage::disk('public')->delete($media->path);
        }
        $pattern->delete();
        return redirect()->route('admin.patterns.index')->with('success', 'Pattern deleted successfully.');
    }
}
