<?php
define('USE_TEST_INPUT', count($argv) > 1 && $argv[1] == '-test');

function computeInputPath($sourceFileName): string
{
	$basePath = preg_replace('/\.php$/', '', $sourceFileName);
	$inputPath = $basePath . (USE_TEST_INPUT ? '.testInput' : '.input') . '.txt';
	if (file_exists($inputPath)) {
		return $inputPath;
	}

	$basePath = preg_replace('/(?:-\d)?\.php$/', '', $sourceFileName);
	$inputPath = $basePath . (USE_TEST_INPUT ? '.testInput' : '.input') . '.txt';
	return $inputPath;
}

function loadLines($sourceFileName): array
{
	$inputPath = computeInputPath($sourceFileName);
	return file($inputPath, FILE_IGNORE_NEW_LINES);
}

function loadInput($sourceFileName): string
{
	$inputPath = computeInputPath($sourceFileName);
	return file_get_contents($inputPath);
}
