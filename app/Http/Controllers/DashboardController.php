<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPaket = Paket::count();
        $totalDelivered = Paket::where('status', 'Delivered')->count();
        $totalPending = Paket::where('status', 'Pending')->count();
        $totalDelivery = Delivery::count();
        $deliveries = Delivery::latest()->paginate(10);
        // $threeHoursAgo = Carbon::now()->subHours(3);
        $oneDayAgo = Carbon::now()->subDay();

        $recentDeliveries = Delivery::where('created_at', '>=', $oneDayAgo)
            ->with(['details.paket.detail', 'details.paket.location']) // optional: load relasi
            ->orderBy('created_at', 'desc')
            ->get();


        return view('dashboard', compact(
            'totalPaket',
            'totalDelivered',
            'totalDelivery',
            'totalPending',
            'deliveries',
            'recentDeliveries'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
