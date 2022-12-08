<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$rows = count($lines);
$columns = strlen($lines[0]);

$grid = implode('', $lines);

function getCell($x, $y): int {
    global $columns, $grid;
    return $grid[$y * $columns + $x];
}

function computeScenicScore($x, $y): int {
    global $columns, $rows;

    $cellValue = getCell($x, $y);
    
    $scenicLeft = 0;
    for ($i = $x - 1; $i >= 0; $i--) {
        $scenicLeft++;
        if (getCell($i, $y) >= $cellValue) {
            break;
        }
    }

    $scenicRight = 0;
    for ($i = $x + 1; $i < $columns; $i++) {
        $scenicRight++;
        if (getCell($i, $y) >= $cellValue) {
            break;
        }
    }
    
    $scenicTop = 0;
    for ($i = $y - 1; $i >= 0; $i--) {
        $scenicTop++;
        if (getCell($x, $i) >= $cellValue) {
            break;
        }
    }
    
    $scenicBottom = 0;
    for ($i = $y + 1; $i < $rows; $i++) {
        $scenicBottom++;
        if (getCell($x, $i) >= $cellValue) {
            break;
        }
    }
    
    return $scenicBottom * $scenicLeft * $scenicRight * $scenicTop;
}

$maxScenicScore = 0;
for ($row = 1; $row < $rows - 1; $row++) {
    for ($column = 1; $column < $columns - 1; $column++) {
        $scenicScore = computeScenicScore($column, $row);
        $maxScenicScore = max($maxScenicScore, $scenicScore);
    }
}
var_dump($maxScenicScore);
