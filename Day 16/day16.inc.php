<?php
define('TOTAL_MINUTES', 30);

class Valve
{
    public array $connections = [];
    public array $bestRoutes = [];

    public function __construct(public readonly string $name, public readonly int $flowRate)
    {
    }

    public function computeBestRoute(string $to)
    {
        $visitedValveNames = [];
        $bestRoute = $this->computeBestRouteRecursive($to, $visitedValveNames, array());
        $this->bestRoutes[$to] = array_values(array_diff($bestRoute, [$this->name]));
    }

    private function computeBestRouteRecursive(string $to, array &$visitedValveNames, array $path): ?array
    {
        if ($to == $this->name) {
            return $path;
        }

        $visitedValveNames[] = $this->name;

        $bestPath = null;
        foreach ($this->connections as $connectedValve) {
            if (in_array($connectedValve->name, $visitedValveNames)) {
                continue;
            }

            $computedPath = $connectedValve->computeBestRouteRecursive($to, $visitedValveNames, array_merge($path, [$this->name]));
            if ($computedPath !== null && ($bestPath === null || count($computedPath) < count($bestPath))) {
                $bestPath = $computedPath;
            }
        }
        return $bestPath;
    }
}

class Graph
{
    private array $valves = [];

    public function addValve(Valve $valve)
    {
        $this->valves[$valve->name] = $valve;
    }

    public function addConnection(string $from, string $to)
    {
        $this->valves[$from]->connections[] = $this->valves[$to];
        $this->valves[$to]->connections[] = $this->valves[$from];
    }

    public function computeMaps()
    {
        $valveNames = array_keys($this->valves);

        for ($i = 0; $i < count($valveNames); $i++) {
            for ($j = 0; $j < count($valveNames); $j++) {
                if ($j == $i) {
                    continue;
                }
                $this->valves[$valveNames[$i]]->computeBestRoute($valveNames[$j]);
            }
        }
    }

    public function getValvesWithPositiveFlowRate(): array
    {
        $possibleValves = array_keys($this->valves);
        $possibleValves = array_filter($possibleValves, function ($valve) {
            return $this->valves[$valve]->flowRate > 0;
        });
        return array_values($possibleValves);
    }

    public function getBestRoute(string $from, string $to): array
    {
        return array_merge($this->valves[$from]->bestRoutes[$to], [$to]);
    }

    public function resolve(array $valveSequence): int
    {
        $currentPosition = 'AA';
        $totalReleasedPressure = 0;
        $openedValves = [];
        $releasedPressurePerMinute = 0;

        $remainingMinutes = TOTAL_MINUTES;
        foreach ($valveSequence as $valveName) {
            $bestRoute = $this->getBestRoute($currentPosition, $valveName);
            $minutesToOpen = count($bestRoute) + 1;
            if ($minutesToOpen >= $remainingMinutes) {
                break;
            }

            $totalReleasedPressure += $releasedPressurePerMinute * $minutesToOpen;
            $remainingMinutes -= $minutesToOpen;

            $openedValves[] = $valveName;
            $releasedPressurePerMinute += $this->valves[$valveName]->flowRate;
            $currentPosition = $valveName;
        }

        $totalReleasedPressure += $releasedPressurePerMinute * $remainingMinutes;
        return $totalReleasedPressure;
    }
}
