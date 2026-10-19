<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminDestinationController extends Controller
{
    /**
     * Districts in Kabupaten Pringsewu.
     *
     * @var array<int, string>
     */
    public const DISTRICTS = [
        'Pringsewu',
        'Gadingrejo',
        'Sukoharjo',
        'Ambarawa',
        'Pagelaran',
        'Pardasuka',
        'Banyumas',
        'Adiluwih',
        'Pagelaran Utara',
    ];

    /**
     * Display a listing of destinations.
     */
    public function index(Request $request): View
    {
        $query = Destination::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && in_array($request->input('category'), ['Wisata', 'UMKM'])) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('district')) {
            $query->where('district', $request->input('district'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['Buka', 'Tutup', 'Musiman'])) {
            $query->where('status', $request->input('status'));
        }

        $destinations = $query->latest()->paginate(10)->withQueryString();
        $districts = self::DISTRICTS;

        return view('admin.destinations.index', compact('destinations', 'districts'));
    }

    /**
     * Show the form for creating a new destination.
     */
    public function create(): View
    {
        $districts = self::DISTRICTS;

        return view('admin.destinations.create', compact('districts'));
    }

    /**
     * Store a newly created destination in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['Wisata', 'UMKM'])],
            'district' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Buka', 'Tutup', 'Musiman'])],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'weekday_price' => ['nullable', 'integer', 'min:0'],
            'weekend_price' => ['nullable', 'integer', 'min:0'],
            'open_hours' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'tags' => ['nullable', 'string'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('destinations', 'public');
            $imagePath = 'storage/'.$path;
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $tagsArray = null;
        if (! empty($validated['tags'])) {
            $tagsArray = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        }

        Destination::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'district' => $validated['district'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'rating' => $validated['rating'] ?? 0,
            'review_count' => $validated['review_count'] ?? 0,
            'description' => $validated['description'] ?? null,
            'long_description' => $validated['long_description'] ?? null,
            'image' => $imagePath,
            'weekday_price' => $validated['weekday_price'] ?? null,
            'weekend_price' => $validated['weekend_price'] ?? null,
            'open_hours' => $validated['open_hours'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'tags' => $tagsArray,
        ]);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "'.$validated['name'].'" berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified destination.
     */
    public function edit(Destination $destination): View
    {
        $districts = self::DISTRICTS;
        $tagsString = is_array($destination->tags) ? implode(', ', $destination->tags) : '';

        return view('admin.destinations.edit', compact('destination', 'districts', 'tagsString'));
    }

    /**
     * Update the specified destination in storage.
     */
    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['Wisata', 'UMKM'])],
            'district' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Buka', 'Tutup', 'Musiman'])],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'weekday_price' => ['nullable', 'integer', 'min:0'],
            'weekend_price' => ['nullable', 'integer', 'min:0'],
            'open_hours' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'tags' => ['nullable', 'string'],
        ]);

        $imagePath = $destination->getRawOriginal('image');

        if ($request->hasFile('image_file')) {
            if ($imagePath && str_starts_with($imagePath, 'storage/destinations/')) {
                $oldFile = str_replace('storage/', '', $imagePath);
                Storage::disk('public')->delete($oldFile);
            }
            $path = $request->file('image_file')->store('destinations', 'public');
            $imagePath = 'storage/'.$path;
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $tagsArray = null;
        if (! empty($validated['tags'])) {
            $tagsArray = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        }

        $destination->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'district' => $validated['district'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'rating' => $validated['rating'] ?? 0,
            'review_count' => $validated['review_count'] ?? 0,
            'description' => $validated['description'] ?? null,
            'long_description' => $validated['long_description'] ?? null,
            'image' => $imagePath,
            'weekday_price' => $validated['weekday_price'] ?? null,
            'weekend_price' => $validated['weekend_price'] ?? null,
            'open_hours' => $validated['open_hours'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'tags' => $tagsArray,
        ]);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "'.$destination->name.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified destination from storage.
     */
    public function destroy(Destination $destination): RedirectResponse
    {
        $rawImage = $destination->getRawOriginal('image');
        if ($rawImage && str_starts_with($rawImage, 'storage/destinations/')) {
            $file = str_replace('storage/', '', $rawImage);
            Storage::disk('public')->delete($file);
        }

        $name = $destination->name;
        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi "'.$name.'" berhasil dihapus.');
    }
}
