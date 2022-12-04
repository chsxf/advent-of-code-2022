<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$matches = [
	'X' => 'A',
	'Y' => 'B',
	'Z' => 'C'
];

$defeats = [
	'X' => 'C',
	'Y' => 'A',
	'Z' => 'B'
];

$baseScore = [
	'X' => 1,
	'Y' => 2,
	'Z' => 3
];

$score = 0;
foreach ($lines as $line) {
	list($theirMove, $myMove) = explode(' ', $line);

	$lineScore = $baseScore[$myMove];
	if ($defeats[$myMove] === $theirMove) {
		$lineScore += 6;
	}
	else if ($matches[$myMove] === $theirMove) {
		$lineScore += 3;
	}

	$score += $lineScore;
}

var_dump($score);