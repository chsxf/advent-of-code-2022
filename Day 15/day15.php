<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day15.inc.php');

$sensors = [];

$minBeaconX = getrandmax();
$maxBeaconX = -getrandmax();
foreach ($lines as $line) {
    preg_match('/^Sensor at x=(-?\d+), y=(-?\d+): closest beacon is at x=(-?\d+), y=(-?\d+)$/', $line, $regs);
    list($_, $xSensor, $ySensor, $xBeacon, $yBeacon) = array_map('intval', $regs);
    $position = new Coords($xSensor, $ySensor);
    $closestBeacon = new Coords($xBeacon, $yBeacon);
    $sensors[] = new Sensor($position, $closestBeacon);

    $minBeaconX = min($minBeaconX, $xBeacon);
    $maxBeaconX = max($maxBeaconX, $xBeacon);
}

define('LINE', USE_TEST_INPUT ? 10 : 2000000);

$sensors = array_filter($sensors, function ($sensor) {
    return ($sensor->isPositionInRange($sensor->position->x, LINE));
});

$impossibleLocationCount = 0;
for ($x = $minBeaconX; $x <= $maxBeaconX; $x++) {
    // Skip existing beacons
    $beaconExistsAtPosition = false;
    foreach ($sensors as $sensor) {
        if ($sensor->closestBeacon->x == $x && $sensor->closestBeacon->y == LINE) {
            $beaconExistsAtPosition = true;
            break;
        }
    }

    if (!$beaconExistsAtPosition) {
        // Is in range of a sensor
        foreach ($sensors as $sensor) {
            if ($sensor->isPositionInRange($x, LINE)) {
                $impossibleLocationCount++;
                break;
            }
        }
    }
}

var_dump($impossibleLocationCount);
