<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day12.inc.php');

$mapWidth = strlen($lines[0]);
$mapData = implode('', $lines);
$map = new Map($mapData, $mapWidth);

var_dump($map->tracePath());
