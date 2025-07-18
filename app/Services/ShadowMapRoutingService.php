<?php

namespace App\Services;

class ShadowMapRoutingService
{
    protected array $graph = [];
    protected array $coordinates = [];

    // public function __construct()
    // {
    //     $this->graph = $this->loadGraphFromCSV(storage_path('app/simpang_jalur_motor_cilandak.json'));
    //     $this->coordinates = $this->getNodeCoordinates(storage_path('app/simpang_jalur_motor_cilandak_coords.json'));
    // }

    // public function loadGraphFromCSV(string $path): array
    // {
    //     $graph = [];
    //     if (!file_exists($path)) {
    //         throw new \Exception("CSV file not found: $path");
    //     }
    //     $rows = array_map('str_getcsv', file($path));
    //     array_shift($rows);
    //     foreach ($rows as [$from, $to, $distance]) {
    //         $graph[$from][$to] = (float) $distance;
    //         $graph[$to][$from] = (float) $distance; // undirected
    //     }
    //     return $graph;
    // }

    // public function getNodeCoordinates(string $path): array
    // {
    //     $coords = [];
    //     if (!file_exists($path)) {
    //         throw new \Exception("Coordinates CSV not found: $path");
    //     }
    //     $rows = array_map('str_getcsv', file($path));
    //     array_shift($rows);
    //     foreach ($rows as [$name, $lat, $lng]) {
    //         $coords[$name] = ['lat' => (float) $lat, 'lng' => (float) $lng];
    //     }
    //     return $coords;
    // }

    public function __construct()
    {
        $this->graph = $this->loadGraphFromJSON(storage_path('app/distance_matrix_simpang_cilandak.json'));
        $this->coordinates = $this->getCoordinatesFromJSON(storage_path('app/location_simpang_cilandak.json'));
    }

    public function loadGraphFromJSON(string $path): array
    {
        if (!file_exists($path)) {
            throw new \Exception("Graph JSON file not found: $path");
        }

        $json = file_get_contents($path);
        return json_decode($json, true);
    }

    public function getCoordinatesFromJSON(string $path): array
    {
        if (!file_exists($path)) {
            throw new \Exception("Coordinates JSON file not found: $path");
        }

        $json = file_get_contents($path);
        return json_decode($json, true);
    }

    public function snapToNearestNode(float $lat, float $lng): ?string
    {
        $minDist = INF;
        $nearest = null;
        foreach ($this->coordinates as $name => $coord) {
            $dist = $this->haversine($lat, $lng, $coord['lat'], $coord['lng']);
            if ($dist < $minDist) {
                $minDist = $dist;
                $nearest = $name;
            }
        }
        return $nearest;
    }

    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    public function runDijkstra(string $start): array
    {
        $dist = array_fill_keys(array_keys($this->graph), INF);
        $prev = array_fill_keys(array_keys($this->graph), null);
        $dist[$start] = 0;
        $queue = $dist;

        while (!empty($queue)) {
            asort($queue);
            $u = array_key_first($queue);
            unset($queue[$u]);

            foreach ($this->graph[$u] as $v => $weight) {
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

    public function getRoute(string $start, string $end): array
    {
        $path = [];
        $result = $this->runDijkstra($start);
        $dist = $result['dist'];
        $prev = $result['prev'];

        $u = $end;
        while ($u !== null) {
            array_unshift($path, $u);
            $u = $prev[$u];
        }

        return ['path' => $path, 'distance_km' => round($dist[$end], 3)];
    }

    public function getCoordinates(string $node): ?array
    {
        return $this->coordinates[$node] ?? null;
    }

    public function getDistanceMatrix(array $nodes): array
    {
        $matrix = [];
        foreach ($nodes as $from) {
            $result = $this->runDijkstra($from);
            foreach ($nodes as $to) {
                $matrix[$from][$to] = $result['dist'][$to];
            }
        }
        return $matrix;
    }

    public function solveTSPBruteForce(array $nodes, string $start): array
    {
        $matrix = $this->getDistanceMatrix($nodes);
        $destinations = array_diff($nodes, [$start]);
        $perms = $this->generatePermutations(array_values($destinations));

        $minDist = INF;
        $bestRoute = [];

        foreach ($perms as $perm) {
            $route = array_merge([$start], $perm, [$start]);
            $dist = $this->calculateRouteDistance($route, $matrix);
            if ($dist < $minDist) {
                $minDist = $dist;
                $bestRoute = $route;
            }
        }

        $optimizedRoute = $this->improve2Opt($bestRoute, $matrix);
        $optimizedDistance = $this->calculateRouteDistance($optimizedRoute, $matrix);

        return ['route' => $optimizedRoute, 'total_distance_km' => $optimizedDistance];
    }

    public function generatePermutations(array $items): array
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

    public function improve2Opt(array $route, array $matrix): array
    {
        $best = $route;
        $improved = true;
        while ($improved) {
            $improved = false;
            for ($i = 1; $i < count($best) - 2; $i++) {
                for ($j = $i + 1; $j < count($best) - 1; $j++) {
                    $newRoute = $best;
                    $segment = array_reverse(array_slice($best, $i, $j - $i + 1));
                    array_splice($newRoute, $i, $j - $i + 1, $segment);
                    if ($this->calculateRouteDistance($newRoute, $matrix) < $this->calculateRouteDistance($best, $matrix)) {
                        $best = $newRoute;
                        $improved = true;
                    }
                }
            }
        }
        return $best;
    }

    public function calculateRouteDistance(array $route, array $matrix): float
    {
        $distance = 0;
        for ($i = 0; $i < count($route) - 1; $i++) {
            $distance += $matrix[$route[$i]][$route[$i + 1]];
        }
        return round($distance, 3);
    }
}
