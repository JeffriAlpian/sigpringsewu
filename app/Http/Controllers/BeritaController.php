<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();

        $beritas = $query->latest('published_at')->paginate(9);

        return view('pages.berita', compact('beritas'));
    }

    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        // Ambil berita terbaru lainnya untuk ditampilkan di aside
        $recentBeritas = Berita::where('id', '!=', $berita->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('pages.berita-detail', compact('berita', 'recentBeritas'));
    }
}
