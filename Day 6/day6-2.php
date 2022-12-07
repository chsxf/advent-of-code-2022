<?php
require_once('../common.inc.php');
$input = loadInput(__FILE__);

define('CHAR_COUNT', 14);

$result = 0;
$buffer = [];
for ($i = 0; $i < strlen($input); $i++) {
    if (count($buffer) == CHAR_COUNT) {
        array_shift($buffer);
    }

    if (count($buffer) < CHAR_COUNT) {
        $buffer[] = $input[$i];
    }

    $uniqueValues = array_unique($buffer);
    if (count($uniqueValues) == CHAR_COUNT) {
        $result = $i + 1;
        break;
    }
}

var_dump($result);