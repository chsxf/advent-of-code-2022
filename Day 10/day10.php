<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

function checkCycle() {
    global $cycle, $currentRegister, $totalSignalStrength;

    if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
        $signalStrength = $cycle * $currentRegister;
        printf("%d * %d = %d\n", $cycle, $currentRegister, $signalStrength);
        $totalSignalStrength += $signalStrength;
    }
}

$cycle = 0;
$currentRegister = 1;
$totalSignalStrength = 0;

foreach ($lines as $line) {
    if ($line == 'noop') {
        $cycle++;
        checkCycle();
        continue;
    }
    
    preg_match('/^addx (-?\d+)$/', $line, $regs);
    $cycle++;
    checkCycle();
    $cycle++;
    checkCycle();
    $currentRegister += $regs[1];
}

var_dump($totalSignalStrength);