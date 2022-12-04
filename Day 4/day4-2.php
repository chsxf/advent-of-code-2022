<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$overlapping = 0;

foreach ($lines as $line) {
	$groups = explode(',', $line);
	$groups = array_map(fn ($item) => explode('-', $item), $groups);

	list($firstGroup, $secondGroup) = $groups;
	if (max($firstGroup) >= min($secondGroup) && min($firstGroup) <= max($secondGroup)) {
		$overlapping++;
	}
}

var_dump($overlapping);