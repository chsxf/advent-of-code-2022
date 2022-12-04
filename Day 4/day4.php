<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$overlapping = 0;

foreach ($lines as $line) {
	$groups = explode(',', $line);
	$groups = array_map(fn ($item) => explode('-', $item), $groups);

	usort($groups, function($a, $b) {
		$aSize = $a[1] - $a[0];
		$bSize = $b[1] - $b[0];
		return $aSize - $bSize;
	});
	
	list($smallerGroup, $biggerGroup) = $groups;
	if ($biggerGroup[0] <= $smallerGroup[0] && $biggerGroup[1] >= $smallerGroup[1]) {
		$overlapping++;
	}
}

var_dump($overlapping);