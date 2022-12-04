<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$priorities = [];
$index = 1;
foreach (range('a', 'z') as $letter) {
	$priorities[$letter] = $index++;
}
foreach (range('A', 'Z') as $letter) {
	$priorities[$letter] = $index++;
}

$sum = 0;
for ($lineIndex = 0; $lineIndex < count($lines); $lineIndex += 3) {
	$line1 = array_unique(str_split($lines[$lineIndex]));
	$line2 = array_unique(str_split($lines[$lineIndex + 1]));
	$line3 = array_unique(str_split($lines[$lineIndex + 2]));

	$intersection = array_values(array_intersect($line1, $line2, $line3));
	$character = $intersection[0];
	$sum += $priorities[$character];
}

var_dump($sum);
