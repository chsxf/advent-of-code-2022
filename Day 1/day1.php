<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$elfSum = 0;
$elfCalories = [];
foreach ($lines as $line) {
	if (empty($line)) {
		if ($elfSum > 0) {
			$elfCalories[] = $elfSum;
			$elfSum = 0;
		}
	}
	else {
		$elfSum += $line;
	}
}

var_dump(max($elfCalories));