<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day16.inc.php');

$graph = new Graph();

$connections = [];
foreach ($lines as $line) {
    preg_match('/^Valve (.+) has flow rate=(\d+); tunnels? leads? to valves? (.+)$/', $line, $regs);
    list($_, $name, $flowRate, $connectedValves) = $regs;

    $valve = new Valve($name, $flowRate);
    $graph->addValve($valve);
    $connections[$name] = explode(', ', $connectedValves);
}

foreach ($connections as $key => $connectedValves) {
    foreach ($connectedValves as $connectedValve) {
        $graph->addConnection($key, $connectedValve);
    }
}

$graph->computeMaps();
$positiveValves = $graph->getValvesWithPositiveFlowRate();

$results = ['sequence' => '', 'rp' => 0];

function iterateOver(array $valves, int $totalLength, array $sequenceSoFar, int $currentLength, int $remainingMinutes, array &$results)
{
    global $graph;

    if ($currentLength == $totalLength || $remainingMinutes <= 0) {
        $releasedPressure = $graph->resolve($sequenceSoFar);
        if ($releasedPressure > $results['rp']) {
            $results['rp'] = $releasedPressure;
            $results['sequence'] = implode(',', $sequenceSoFar);
        }
        return;
    }

    foreach ($valves as $valveName) {
        if (in_array($valveName, $sequenceSoFar)) {
            continue;
        }

        $from = ($currentLength == 0) ? 'AA' : $sequenceSoFar[$currentLength - 1];
        $bestRoute = $graph->getBestRoute($from, $valveName);
        $minutesToOpen = count($bestRoute) + 1;
        if ($minutesToOpen >= $remainingMinutes) {
            iterateOver($valves, $totalLength, $sequenceSoFar, $currentLength, -1, $results);
        } else {
            $newSequence = $sequenceSoFar;
            $newSequence[] = $valveName;
            iterateOver($valves, $totalLength, $newSequence, $currentLength + 1, $remainingMinutes - $minutesToOpen, $results);
        }
    }
}

$start = microtime(true);
iterateOver($positiveValves, count($positiveValves), array(), 0, TOTAL_MINUTES, $results);
$elapsed = microtime(true) - $start;

printf("Best Result: %s => %d\n", $results['sequence'], $results['rp']);
printf("Processing time: %0.4f s (%0.2f ms)\n", $elapsed, $elapsed * 1000);
