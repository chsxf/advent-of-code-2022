<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day15.inc.php');

$sensors = [];

$minBeaconXY = 0;
$maxBeaconXY = USE_TEST_INPUT ? 20 : 4000000;
foreach ($lines as $line) {
    preg_match('/^Sensor at x=(-?\d+), y=(-?\d+): closest beacon is at x=(-?\d+), y=(-?\d+)$/', $line, $regs);
    list($_, $xSensor, $ySensor, $xBeacon, $yBeacon) = array_map('intval', $regs);
    $position = new Coords($xSensor, $ySensor);
    $closestBeacon = new Coords($xBeacon, $yBeacon);
    $sensors[] = new Sensor($position, $closestBeacon);
}

$startTime = microtime(true);

for ($y = $minBeaconXY; $y <= $maxBeaconXY; $y++) {
    $lineSensors = array_filter($sensors, function ($sensor) {
        global $y;
        return $sensor->isPositionInRange($sensor->position->x, $y);
    });

    $line = $y - $minBeaconXY;
    if ($line > 0 && $line % 100000 == 0) {
        printf("Line %d - %d sensors (Elapsed: %f)\n", $line, count($lineSensors), microtime(true) - $startTime);
    }

    for ($x = $minBeaconXY; $x <= $maxBeaconXY; $x++) {
        $inRangeOfSensor = false;
        // Is in range of a sensor
        foreach ($lineSensors as $sensor) {
            if ($sensor->isPositionInRange($x, $y)) {
                $inRangeOfSensor = true;
                $x = $sensor->horizontalMaxRangeLimit($y);
                break;
            }
        }

        if (!$inRangeOfSensor) {
            $tuningFrequency = $x * 4000000 + $y;
            var_dump($tuningFrequency);
            exit();
        }
    }
}
