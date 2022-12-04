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
foreach ($lines as $line) {
	$lineLength = strlen($line);
	$halfSize = $lineLength >> 1;

	$firstHalf = substr($line, 0, $halfSize);
	$firstChars = array_unique(str_split($firstHalf));

	$secondHalf = substr($line, $halfSize);
	$secondChars = array_unique(str_split($secondHalf));

	$intersection = array_values(array_intersect($firstChars, $secondChars));
	$character = $intersection[0];

	$sum += $priorities[$character];
}

var_dump($sum);
