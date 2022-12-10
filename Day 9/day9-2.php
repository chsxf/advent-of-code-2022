<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day9.inc.php');

define('LAST_KNOT', 9);

$knots = [];
for ($i = 0; $i <= LAST_KNOT; $i++) {
    $knots[] = new Position();
}

$tailKnownPositions = [strval($knots[LAST_KNOT])];

function dumpGrid()
{
    global $knots;

    for ($y = 4; $y >= 0; $y--) {
        for ($x = 0; $x < 6; $x++) {
            $found = false;
            for ($i = 0; $i < count($knots); $i++) {
                if ($knots[$i]->x == $x && $knots[$i]->y == $y) {
                    printf(($i == 0) ? 'H' : $i);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                printf('.');
            }
        }
        printf("\n");
    }
    printf("\n");
}

foreach ($lines as $line) {
    list($directionValue, $amount) = explode(' ', $line);
    $direction = Direction::from($directionValue);

    //printf("Line: %s\n============\n\n", $line);

    for ($i = 0; $i < $amount; $i++) {
        //printf("Step #%d\n-------------\n\n", $i + 1);

        $knots[0]->moveTowards($direction);
        for ($j = 1; $j <= LAST_KNOT; $j++) {
            $knots[$j]->catchUpWith($knots[$j - 1]);
        }
        $tailKnownPositions[] = strval($knots[LAST_KNOT]);

        //dumpGrid();
    }
}

$tailKnownPositions = array_unique($tailKnownPositions);
var_dump(count($tailKnownPositions));
