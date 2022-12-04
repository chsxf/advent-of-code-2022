<?php
define('USE_TEST_INPUT', count($argv) > 1 && $argv[1] == '-test');

function loadLines($sourceFileName): array {
	$basePath = preg_replace('/(?:-\d)?\.php$/', '', $sourceFileName);
	$inputPath = $basePath . (USE_TEST_INPUT ? '.testInput' : '.input') . '.txt';
	return file($inputPath, FILE_IGNORE_NEW_LINES);
}