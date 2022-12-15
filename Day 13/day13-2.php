<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__, skipEmptyLines: true);

define('FIRST_MARKER', '[[2]]');
define('SECOND_MARKER', '[[6]]');

enum Status: string
{
    case ordered = 'Valid';
    case unordered = 'Invalid';
    case continue = 'Continue';
}

$lines[] = FIRST_MARKER;
$lines[] = SECOND_MARKER;

$lines = array_map('json_decode', $lines);

usort($lines, function ($a, $b) {
    $result = compareArrays($a, $b);
    if ($result == Status::ordered) {
        return -1;
    } else if ($result == Status::unordered) {
        return 1;
    }
});

$lines = array_map('json_encode', $lines);

$firstMarkerIndex = array_search(FIRST_MARKER, $lines) + 1;
$secondMarkerIndex = array_search(SECOND_MARKER, $lines) + 1;
var_dump($firstMarkerIndex * $secondMarkerIndex);

function compareArrays(array $a, array $b, int $level = 0): Status
{
    $indent = str_pad('', $level, "\t");
    $indent .= "\t\t";

    $indent .= "\t";

    $maxCount = max(count($a), count($b));

    for ($i = 0; $i < $maxCount; $i++) {
        $aExists = array_key_exists($i, $a);
        $bExists = array_key_exists($i, $b);

        if (!$aExists && $bExists) {
            return Status::ordered;
        }

        if ($aExists && !$bExists) {
            return Status::unordered;
        }

        $itemA = $a[$i];
        $itemB = $b[$i];

        if (!is_array($itemA) && !is_array($itemB)) {
            if ($itemA > $itemB) {
                return Status::unordered;
            } else if ($itemA < $itemB) {
                return Status::ordered;
            }
        } else if (is_array($itemA) && is_array($itemB)) {
            $newStatus = compareArrays($itemA, $itemB, $level + 1);
            if ($newStatus != Status::continue) {
                return $newStatus;
            }
        } else {
            if (is_array($itemA) && !is_array($itemB)) {
                $itemB = [$itemB];
            } else if (is_array($itemB) && !is_array($itemA)) {
                $itemA = [$itemA];
            }
            $newStatus = compareArrays($itemA, $itemB, $level + 1);
            if ($newStatus != Status::continue) {
                return $newStatus;
            }
        }
    }

    return Status::continue;
}
