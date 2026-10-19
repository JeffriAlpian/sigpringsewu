<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminBeritaController extends Controller
{
    /**
     * Display a listing of berita.
     */
    public function index(Request $request): View
    {
        $query = Berita::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $beritas = $query->latest('published_at')->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new berita.
     */
    public function create(): View
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created berita in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:berita,slug'],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:1000'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('berita', 'public');
            $imagePath = 'storage/'.$path;
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        Berita::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? now(),
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita "'.$validated['title'].'" berhasil diterbitkan.');
    }

    /**
     * Show the form for editing the specified berita.
     */
    public function edit(Berita $berita): View
    {
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified berita in storage.
     */
    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('berita', 'slug')->ignore($berita->id)],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:1000'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Berita::where('slug', $slug)->where('id', '!=', $berita->id)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $imagePath = $berita->getRawOriginal('image');

        if ($request->hasFile('image_file')) {
            if ($imagePath && str_starts_with($imagePath, 'storage/berita/')) {
                $oldFile = str_replace('storage/', '', $imagePath);
                Storage::disk('public')->delete($oldFile);
            }
            $path = $request->file('image_file')->store('berita', 'public');
            $imagePath = 'storage/'.$path;
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $berita->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? $berita->published_at,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita "'.$berita->title.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified berita from storage.
     */
    public function destroy(Berita $berita): RedirectResponse
    {
        $rawImage = $berita->getRawOriginal('image');
        if ($rawImage && str_starts_with($rawImage, 'storage/berita/')) {
            $file = str_replace('storage/', '', $rawImage);
            Storage::disk('public')->delete($file);
        }

        $title = $berita->title;
        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita "'.$title.'" berhasil dihapus.');
    }
}
