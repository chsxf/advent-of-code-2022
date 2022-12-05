<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

$emptyLineIndex = array_search('', $lines, true);
$stackLines = array_reverse(array_slice($lines, 0, $emptyLineIndex - 1));
$moveLines = array_slice($lines, $emptyLineIndex + 1);

$stacks = [];
$stackCount = ceil(strlen($stackLines[0]) / 4);
for ($i = 0; $i < $stackCount; $i++) {
    $stacks[] = [];
}
foreach ($stackLines as $line) {
    $index = 1;
    for ($i = 0; $i < $stackCount; $i++) {
        $crate = trim($line[$index]);
        if (!empty($crate)) {
            $stacks[$i][] = $crate;
        }
        $index += 4;
    }
}

function move($count, $from, $to) {
    global $stacks;

    $crates = array_splice($stacks[$from], -$count);
    $stacks[$to] = array_merge($stacks[$to], $crates);
}

foreach ($moveLines as $line) {
    preg_match('/^move (\d+) from (\d+) to (\d+)$/', $line, $regs);

    list($_, $count, $from, $to) = $regs;

    move($count, $from - 1, $to - 1);
}

$result = '';
foreach ($stacks as $stack) {
    if (!empty($stack)) {
        $result .= $stack[count($stack) - 1];
    }
}
var_dump($result);