<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

require_once('day11.inc.php');

$monkeys = [];

$i = 1;
while ($i < count($lines)) {
    preg_match('/: ([ ,0-9]+)$/', $lines[$i++], $regs);
    $startingItems = str_replace(' ', '', $regs[1]);
    $startingItems = explode(',', $startingItems);
    $startingItems = array_map('intval', $startingItems);

    preg_match('/new = old (\*|\+) (old|\d+)$/', $lines[$i++], $regs);
    list($_, $operator, $value) = $regs;
    if ($operator === '+') {
        $operation = new AddOperation($value);
    } else if ($value === 'old') {
        $operation = new PowerOperation();
    } else {
        $operation = new MultiplyOperation($value);
    }

    preg_match('/by (\d+)$/', $lines[$i++], $regs);
    $divider = $regs[1];

    preg_match('/monkey (\d+)$/', $lines[$i++], $regs);
    $trueDestination = $regs[1];

    preg_match('/monkey (\d+)$/', $lines[$i++], $regs);
    $falseDestination = $regs[1];

    $monkeys[] = new Monkey($startingItems, $operation, $divider, $trueDestination, $falseDestination);

    $i += 2;
}

define('ROUNDS', 20);

for ($round = 0; $round < ROUNDS; $round++) {
    for ($i = 0; $i < count($monkeys); $i++) {
        $monkey = $monkeys[$i];
        while ($item = $monkey->inspect()) {
            $item = $monkey->operation->apply($item);
            $item = intval($item / 3);
            $isTrue = ($item % $monkey->divider == 0);
            if ($isTrue) {
                $monkeys[$monkey->trueDestination]->push($item);
            } else {
                $monkeys[$monkey->falseDestination]->push($item);
            }
        }
    }
}

$inspectionCounts = array_map(fn ($monkey) => $monkey->getInspectionCount(), $monkeys);
rsort($inspectionCounts);
var_dump($inspectionCounts[0] * $inspectionCounts[1]);
