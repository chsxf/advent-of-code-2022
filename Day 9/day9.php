<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day9.inc.php');

$headPosition = new Position();
$tailPosition = new Position();

$tailKnownPositions = [strval($tailPosition)];

foreach ($lines as $line) {
    list($directionValue, $amount) = explode(' ', $line);
    $direction = Direction::from($directionValue);

    for ($i = 0; $i < $amount; $i++) {
        $headPosition->moveTowards($direction);
        $tailPosition->catchUpWith($headPosition);
        $tailKnownPositions[] = strval($tailPosition);
    }
}

$tailKnownPositions = array_unique($tailKnownPositions);
var_dump(count($tailKnownPositions));
