<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day14.inc.php');

$lines = array_map(function ($item) {
    $points = explode(' -> ', $item);
    $points = array_map(fn ($p) => new Point($p), $points);
    return $points;
}, $lines);

// Computing max Y
$maxY = -getrandmax();
foreach ($lines as $line) {
    foreach ($line as $point) {
        $maxY = max($maxY, $point->y);
    }
}

$map = new Map($maxY);

foreach ($lines as $line) {
    $firstPoint = $line[0];
    for ($i = 1; $i < count($line); $i++) {
        $map->drawWall($firstPoint, $line[$i]);
        $firstPoint = $line[$i];
    }
}

$sandDropPosition = new Point('500,0');
$sandCount = 0;
while ($map->dropSand($sandDropPosition)) {
    $sandCount++;
}

$outputPath = computeOutputPath(__FILE__);
$fp = USE_TEST_INPUT ? STDOUT : fopen($outputPath, 'w');
$map->dumpGrid($fp);
if (!USE_TEST_INPUT) {
    fclose($fp);
}

var_dump($sandCount);
