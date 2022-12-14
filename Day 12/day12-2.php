<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day12.inc.php');

$mapWidth = strlen($lines[0]);
$mapData = implode('', $lines);
$mapData = str_replace('S', 'a', $mapData);

$aIndices = [];
$aPos = -1;
while (($aPos = strpos($mapData, 'a', $aPos + 1)) !== false) {
    $aIndices[] = $aPos;
}
printf("Found %d 'a'\n", count($aIndices));

$map = new Map($mapData, $mapWidth);
$map->tracePath();

$minLength = getrandmax();
foreach ($aIndices as $aIndex) {
    $traceValue = $map->getTraceValue($aIndex);
    if ($traceValue > 0) {
        $minLength = min($minLength, $traceValue);
    }
}
var_dump($minLength);
