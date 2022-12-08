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

function isVisible($x, $y): bool {
    global $columns, $rows;

    $cellValue = getCell($x, $y);
    
    $visibleOnLeft = true;
    for ($i = $x - 1; $i >= 0; $i--) {
        if (getCell($i, $y) >= $cellValue) {
            $visibleOnLeft = false;
            break;
        }
    }

    $visibleOnRight = true;
    for ($i = $x + 1; $i < $columns; $i++) {
        if (getCell($i, $y) >= $cellValue) {
            $visibleOnRight = false;
            break;
        }
    }
    
    $visibleOnTop = true;
    for ($i = $y - 1; $i >= 0; $i--) {
        if (getCell($x, $i) >= $cellValue) {
            $visibleOnTop = false;
            break;
        }
    }
    
    $visibleOnBottom = true;
    for ($i = $y + 1; $i < $rows; $i++) {
        if (getCell($x, $i) >= $cellValue) {
            $visibleOnBottom = false;
            break;
        }
    }
    
    return $visibleOnBottom || $visibleOnLeft || $visibleOnTop || $visibleOnRight;
}

$visibleTrees = $columns * 2 + ($rows - 2) * 2;
for ($row = 1; $row < $rows - 1; $row++) {
    for ($column = 1; $column < $columns - 1; $column++) {
        $visible = isVisible($column, $row);
        if ($visible) {
            $visibleTrees++;
        }
    }
}
var_dump($visibleTrees);
