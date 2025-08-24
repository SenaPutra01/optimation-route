<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Notification;
use App\Models\Paket;
use App\Models\User;
use App\Services\ShadowMapRoutingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    // protected ShadowMapRoutingService $routing;

    // public function __construct(ShadowMapRoutingService $routing)
    // {
    //     $this->routing = $routing;
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::latest()->paginate(10);
        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $couriers = User::all();

        $pakets = Paket::with('detail')
            ->whereDoesntHave('deliveryDetails')
            ->get();

        return view('deliveries.partials.create', compact('couriers', 'pakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'courier_id' => 'required|exists:users,id',
            'paket_ids' => 'required|array|min:1',
            'paket_ids.*' => 'exists:pakets,id',
        ]);

        $courier = User::findOrFail($validated['courier_id']);

        DB::beginTransaction();

        try {
            $today = now()->startOfDay();
            $existingDelivery = Delivery::where('courier_name', $courier->name)
                ->whereDate('scheduled_at', $today)
                ->first();

            if (!$existingDelivery) {
                $kode_pengiriman = 'DLV' . now()->format('Ymd') . strtoupper(Str::random(5));

                $existingDelivery = Delivery::create([
                    'status' => 'Pending',
                    'courier_name' => $courier->name,
                    'scheduled_at' => now(),
                    'kode_pengiriman' => $kode_pengiriman,
                ]);
            }

            foreach ($validated['paket_ids'] as $paketId) {
                DeliveryDetail::firstOrCreate([
                    'delivery_id' => $existingDelivery->id,
                    'paket_id' => $paketId,
                ]);

                Paket::where('id', $paketId)->update([
                    'status' => 'Delivered',
                ]);
            }

            $this->getOptimizedRoute($existingDelivery->kode_pengiriman);

            Notification::create([
                'user_id' => $courier->id,
                'title' => 'Pengiriman Baru',
                'message' => 'Anda ditugaskan untuk pengiriman kode: ' . $kode_pengiriman,
                'delivery_id' => $existingDelivery->id,
            ]);



            DB::commit();

            session()->flash('popup_notification', [
                'title' => 'Pengiriman Baru',
                'message' => 'Anda ditugaskan untuk pengiriman kode: ' . $kode_pengiriman,
            ]);

            return redirect()->route('deliveries.index')->with(['success' => 'Paket berhasil diassign ke pengiriman', 'kode_paket' => $kode_pengiriman]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('deliveries.create')->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $delivery = Delivery::with(['details.paket', 'route'])
            ->where('id', $id)
            // ->orWhere('kode_pengiriman', $kodePengirimanOrId)
            ->firstOrFail();
        $routeDetails = $delivery->route?->route_details ?? [];

        // return response()->json($routeDetails);
        return view('deliveries.partials.show', compact('delivery', 'routeDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $delivery = Delivery::with('pakets.detail')->findOrFail($id);
        $couriers = User::all();

        $pakets = Paket::with('detail')->where(function ($query) use ($delivery) {
            $query->whereDoesntHave('deliveryDetails') // belum pernah diassign
                ->orWhereHas('deliveryDetails', function ($q) use ($delivery) {
                    $q->where('delivery_id', $delivery->id); // sudah bagian dari pengiriman ini
                });
        })->get();

        // dd($pakets);
        return view('deliveries.partials.edit', compact('delivery', 'couriers', 'pakets'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'courier_name' => 'required|string',
            'paket_ids' => 'required|array|min:1'
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->update([
            'courier_name' => $request->courier_name,
            'notes' => $request->notes,
        ]);

        $delivery->details()->delete();
        foreach ($request->paket_ids as $paketId) {
            DeliveryDetail::create([
                'delivery_id' => $delivery->id,
                'paket_id' => $paketId
            ]);
        }

        $this->getOptimizedRoute($delivery->kode_pengiriman);


        return redirect()->route('deliveries.index')->with('success', 'Pengiriman berhasil diperbarui.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOptimizedRoute($kode_pengiriman)
    {
        $delivery = Delivery::with(['details.paket.location', 'route'])
            ->where('kode_pengiriman', $kode_pengiriman)
            ->firstOrFail();

        if ($delivery->route) {
            return response()->json([
                'optimized_route' => $delivery->route->optimized_route,
                'total_distance_km' => $delivery->route->total_distance_km,
                'distance_matrix' => $delivery->route->distance_matrix,
                'route_details' => $delivery->route->route_details,
                'cached' => true
            ]);
        }

        // Build locations
        $locations = $delivery->details->map(function ($detail) {
            return [
                'name' => $detail->paket->detail->nama_penerima ?? ('Paket #' . $detail->paket->id),
                'lat' => $detail->paket->location->lat ?? null,
                'lng' => $detail->paket->location->lng ?? null,
            ];
        })->filter(fn($loc) => $loc['lat'] && $loc['lng'])->values()->toArray();

        $warehouse = [
            'name' => 'Warehouse',
            'lat' => -6.311175188123682,
            'lng' => 106.80015942879892,
        ];
        array_unshift($locations, $warehouse);

        $graph = $this->buildGraphFromLocations($locations);

        $distanceMatrix = [];
        foreach ($graph as $from => $_) {
            $result = $this->runDijkstra($graph, $from);
            foreach ($graph as $to => $_) {
                $distanceMatrix[$from][$to] = $result['dist'][$to];
            }
        }

        $start = 'Warehouse';
        $points = array_keys($distanceMatrix);
        $destinations = array_diff($points, [$start]);

        // Brute Force or Nearest Neighbor + 2OPT
        if (count($destinations) <= 8) {
            $permutations = $this->generatePermutations(array_values($destinations));
            $minDist = INF;
            $bestRoute = [];
            foreach ($permutations as $perm) {
                $route = array_merge([$start], $perm, [$start]);
                $dist = 0;
                for ($i = 0; $i < count($route) - 1; $i++) {
                    $dist += $distanceMatrix[$route[$i]][$route[$i + 1]];
                }
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $bestRoute = $route;
                }
            }
        } else {
            $bestRoute = $this->tspNearestNeighbor($distanceMatrix, $start);
            $minDist = 0;
            for ($i = 0; $i < count($bestRoute) - 1; $i++) {
                $minDist += $distanceMatrix[$bestRoute[$i]][$bestRoute[$i + 1]];
            }
        }

        // Fetch route details from OSRM
        $locationMap = collect($locations)->keyBy('name');
        $routeDetails = [];
        for ($i = 0; $i < count($bestRoute) - 1; $i++) {
            $from = $locationMap[$bestRoute[$i]];
            $to = $locationMap[$bestRoute[$i + 1]];

            $segment = $this->fetchRouteDetail($from, $to);
            $routeDetails[] = [
                'from' => $bestRoute[$i],
                'to' => $bestRoute[$i + 1],
                'distance_km' => round($segment['distance'], 3),
                'duration_min' => round($segment['duration'], 2),
                'path_coordinates' => $segment['coordinates']
            ];
        }

        // Simpan hasil ke tabel delivery_routes
        $delivery->route()->create([
            'optimized_route' => $bestRoute,
            'distance_matrix' => $distanceMatrix,
            'route_details' => $routeDetails,
            'total_distance_km' => round($minDist, 3),
        ]);

        return response()->json([
            'optimized_route' => $bestRoute,
            'total_distance_km' => round($minDist, 3),
            'distance_matrix' => $distanceMatrix,
            'route_details' => $routeDetails,
            'cached' => false
        ]);
    }

    private function buildGraphFromLocations(array $locations)
    {
        $graph = [];
        foreach ($locations as $from) {
            foreach ($locations as $to) {
                if ($from['name'] === $to['name']) continue;

                $distance = $this->getCachedORSdistance($from, $to);
                $graph[$from['name']][$to['name']] = $distance;
            }
        }
        return $graph;
    }

    private function runDijkstra(array $graph, string $start)
    {
        $dist = array_fill_keys(array_keys($graph), INF);
        $prev = array_fill_keys(array_keys($graph), null);
        $dist[$start] = 0;
        $queue = $dist;

        while (!empty($queue)) {
            asort($queue);
            $u = array_key_first($queue);
            unset($queue[$u]);

            foreach ($graph[$u] as $v => $weight) {
                $alt = $dist[$u] + $weight;
                if ($alt < $dist[$v]) {
                    $dist[$v] = $alt;
                    $prev[$v] = $u;
                    $queue[$v] = $alt;
                }
            }
        }

        return ['dist' => $dist, 'prev' => $prev];
    }

    private function generatePermutations(array $items): array
    {
        $result = [];
        $this->buildPermutations($items, [], $result);
        return $result;
    }

    private function buildPermutations(array $items, array $perms, array &$result)
    {
        if (empty($items)) {
            $result[] = $perms;
        } else {
            for ($i = 0; $i < count($items); $i++) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_push($newperms, $foo);
                $this->buildPermutations($newitems, $newperms, $result);
            }
        }
    }

    private function tspNearestNeighbor(array $matrix, string $start): array
    {
        $unvisited = array_keys($matrix);
        $current = $start;
        $route = [$current];
        unset($unvisited[array_search($current, $unvisited)]);

        while (!empty($unvisited)) {
            $nearest = null;
            $minDist = INF;
            foreach ($unvisited as $i => $loc) {
                if ($matrix[$current][$loc] < $minDist) {
                    $nearest = $loc;
                    $minDist = $matrix[$current][$loc];
                }
            }
            $route[] = $nearest;
            $current = $nearest;
            unset($unvisited[array_search($current, $unvisited)]);
        }

        $route[] = $start;
        return $route;
    }

    private function getCachedORSdistance(array $from, array $to)
    {
        $cacheFile = 'ors_cache.json';
        $cache = [];

        if (Storage::exists($cacheFile)) {
            $cache = json_decode(Storage::get($cacheFile), true);
        }

        $fromName = $from['name'];
        $toName = $to['name'];

        if (isset($cache[$fromName][$toName])) {
            return $cache[$fromName][$toName];
        }

        $apiKey = env('ORS_API_KEY');
        $coordinates = [[$from['lng'], $from['lat']], [$to['lng'], $to['lat']]];

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car', [
            'coordinates' => $coordinates,
            'preference' => 'shortest',
            'options' => [
                'avoid_features' => ['highways', 'tollways', 'ferries'],
            ],
        ]);

        $data = $response->json();
        $distance = $data['routes'][0]['summary']['distance'] ?? null;

        if ($distance !== null) {
            $km = round($distance / 1000, 3);
            $cache[$fromName][$toName] = $km;
            $cache[$toName][$fromName] = $km;
            Storage::put($cacheFile, json_encode($cache, JSON_PRETTY_PRINT));
            return $km;
        }

        return INF;
    }

    public function snapToNearest($lat, $lng)
    {
        $url = "https://router.project-osrm.org/nearest/v1/driving/{$lng},{$lat}";
        $response = Http::timeout(10)->get($url);

        if ($response->successful() && isset($response['waypoints'][0]['location'])) {
            $location = $response['waypoints'][0]['location'];
            return [
                'lat' => $location[1],
                'lng' => $location[0],
            ];
        }

        return [
            'lat' => $lat,
            'lng' => $lng,
        ];
    }

    public function fetchRouteDetail(array $from, array $to)
    {
        $url = "https://router.project-osrm.org/route/v1/driving/{$from['lng']},{$from['lat']};{$to['lng']},{$to['lat']}?overview=full&geometries=geojson";
        $res = Http::timeout(10)->get($url);

        if (!$res->successful() || empty($res['routes'])) {
            $snappedFrom = $this->snapToNearest($from['lat'], $from['lng']);
            $snappedTo = $this->snapToNearest($to['lat'], $to['lng']);

            $retryUrl = "https://router.project-osrm.org/route/v1/driving/{$snappedFrom['lng']},{$snappedFrom['lat']};{$snappedTo['lng']},{$snappedTo['lat']}?overview=full&geometries=geojson";
            $retryRes = Http::timeout(10)->get($retryUrl);

            if (!$retryRes->successful() || empty($retryRes['routes'])) {
                throw new \Exception("No route found after retry");
            }

            $route = $retryRes['routes'][0];
        } else {
            $route = $res['routes'][0];
        }

        return [
            'coordinates' => array_map(fn($coord) => [$coord[1], $coord[0]], $route['geometry']['coordinates']),
            'distance' => $route['distance'] / 1000,
            'duration' => $route['duration'] / 60
        ];
    }
}
