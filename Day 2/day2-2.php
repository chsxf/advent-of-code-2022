<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$defeats = [
	'A' => 'C',
	'B' => 'A',
	'C' => 'B'
];

$baseScore = [
	'A' => 1,
	'B' => 2,
	'C' => 3
];

$outcomeScore = [
	'X' => 0,
	'Y' => 3,
	'Z' => 6
];

$score = 0;
foreach ($lines as $line) {
	list($theirMove, $outcome) = explode(' ', $line);

	$lineScore = $outcomeScore[$outcome];

	switch ($outcome) {
		case 'X': // lose
			$myMove = $defeats[$theirMove];
			break;

		case 'Y': // tie
			$myMove = $theirMove;
			break;

		case 'Z': // win
			unset($myMove);
			foreach ($defeats as $win => $over) {
				if ($over === $theirMove) {
					$myMove = $win;
					break;
				}
			}
			if (!isset($myMove)) {
				die('$myMove should be set');
			}
			break;
	}

	$lineScore += $baseScore[$myMove];
	$score += $lineScore;
}

var_dump($score);