<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\RoutePolyline;
use App\Models\RouteSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function getRouteSummary($kodePengiriman)
    {
        $summary = RouteSummary::where('kode_pengiriman', $kodePengiriman)->first();
        $segments = RoutePolyline::where('kode_pengiriman', $kodePengiriman)->get();

        if (!$summary || $segments->isEmpty()) {
            return response()->json(['message' => 'Belum ada data rute'], 404);
        }

        return response()->json([
            'kode_pengiriman' => $summary->kode_pengiriman,
            'total_distance_km' => $summary->total_distance_km,
            'total_duration_min' => $summary->total_duration_min,
            'fuel_cost_per_km' => $summary->fuel_cost_per_km,
            'total_fuel_cost' => $summary->total_fuel_cost,
            'segments' => $segments->map(function ($seg) {
                return [
                    'from' => $seg->from,
                    'to' => $seg->to,
                    'distance' => $seg->distance_km,
                    'duration' => $seg->duration_min,
                    'path' => json_decode($seg->coordinates_json),
                ];
            })
        ]);
    }

    public function getRouteData($kodePengiriman)
    {
        $delivery = Delivery::with(['details.paket.detail', 'details.paket.location'])
            ->where('kode_pengiriman', $kodePengiriman)->firstOrFail();

        $nodes = collect();
        foreach ($delivery->details as $detail) {
            $paket = $detail->paket;
            $loc = $paket->location;
            $nodes->push([
                'name' => $loc->name,
                'lat' => $loc->lat,
                'lng' => $loc->lng
            ]);
        }

        $warehouse = [
            'name' => 'Warehouse',
            'lat' => config('app.warehouse_lat', -6.2959),
            'lng' => config('app.warehouse_lng', 106.8151)
        ];

        return response()->json([
            'nodes' => collect([$warehouse])->merge($nodes)->toArray(),
            'route' => collect([$warehouse['name']])->merge($nodes->pluck('name'))->push($warehouse['name'])->toArray()
        ]);
    }

    public function storeSummaryPolyline(Request $request)
    {
        $validated = $request->validate([
            'kode_pengiriman' => 'required|string',
            'segments' => 'required|array',
            'total_distance_km' => 'required|numeric',
            'total_duration_min' => 'required|numeric',
            'fuel_cost_per_km' => 'required|numeric',
            'total_fuel_cost' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            RouteSummary::updateOrCreate(
                ['kode_pengiriman' => $validated['kode_pengiriman']],
                [
                    'total_distance_km' => $validated['total_distance_km'],
                    'total_duration_min' => $validated['total_duration_min'],
                    'fuel_cost_per_km' => $validated['fuel_cost_per_km'],
                    'total_fuel_cost' => $validated['total_fuel_cost'],
                ]
            );

            RoutePolyline::where('kode_pengiriman', $validated['kode_pengiriman'])->delete();

            foreach ($validated['segments'] as $segment) {
                RoutePolyline::create([
                    'kode_pengiriman' => $validated['kode_pengiriman'],
                    'from' => $segment['from'],
                    'to' => $segment['to'],
                    'distance_km' => $segment['distance'],
                    'duration_min' => $segment['duration'],
                    'coordinates_json' => json_encode($segment['path']),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Rute berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan rute', 'details' => $e->getMessage()], 500);
        }
    }
}
