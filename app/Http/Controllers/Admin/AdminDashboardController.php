<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard overview.
     */
    public function index(): View
    {
        $totalDestinations = Destination::count();
        $totalWisata = Destination::where('category', 'Wisata')->count();
        $totalUmkm = Destination::where('category', 'UMKM')->count();
        $totalBerita = Berita::count();
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

        $recentDestinations = Destination::latest()->take(5)->get();
        $recentBerita = Berita::latest('published_at')->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        $districtStats = Destination::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->groupBy('district')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'totalDestinations',
            'totalWisata',
            'totalUmkm',
            'totalBerita',
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'recentDestinations',
            'recentBerita',
            'recentUsers',
            'districtStats'
        ));
    }
}
