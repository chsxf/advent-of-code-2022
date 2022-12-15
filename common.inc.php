<?php
define('USE_TEST_INPUT', count($argv) > 1 && $argv[1] == '-test');

function computeInputPath(string $sourceFileName): string
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

function computeOutputPath(string $sourceFileName): string
{
	$suffix = USE_TEST_INPUT ? 'testOutput' : 'output';
	return "{$sourceFileName}.{$suffix}.txt";
}

function loadLines(string $sourceFileName, bool $skipEmptyLines = false): array
{
	$inputPath = computeInputPath($sourceFileName);
	$flags = FILE_IGNORE_NEW_LINES;
	if ($skipEmptyLines) {
		$flags |= FILE_SKIP_EMPTY_LINES;
	}
	return file($inputPath, $flags);
}

function loadInput(string $sourceFileName): string
{
	$inputPath = computeInputPath($sourceFileName);
	return file_get_contents($inputPath);
}
