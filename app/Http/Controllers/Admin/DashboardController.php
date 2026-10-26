<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Pesanan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKos = Kos::count();
        $totalPesanan = Pesanan::count();
        $pesananMenunggu = Pesanan::where('status', 'menunggu')->count();
        $totalUser = User::where('role', 'user')->count();

        $pesananTerbaru = Pesanan::with(['user', 'kos'])
            ->latest()
            ->take(5)
            ->get();

        return view('Admin.dashboard', compact(
            'totalKos',
            'totalPesanan',
            'pesananMenunggu',
            'totalUser',
            'pesananTerbaru'
        ));
    }
}
