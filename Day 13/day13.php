<?php
require_once('../common.inc.php');
$lines = loadLines(__FILE__);

enum Status: string
{
    case valid = 'Valid';
    case invalid = 'Invalid';
    case continue = 'Continue';
}

$outputPath = computeOutputPath(__FILE__);
$fp = fopen($outputPath, 'w');

$validPairs = [];
for ($i = 0, $pairIndex = 1; $i < count($lines); $i += 3, $pairIndex++) {
    $firstPacket = json_decode($lines[$i]);
    $secondPacket = json_decode($lines[$i + 1]);

    fprintf($fp, "-------------- Pair #%d --------------\n", $pairIndex);
    fprintf($fp, "First packet: %s\n", $lines[$i]);
    fprintf($fp, "Second packet: %s\n", $lines[$i + 1]);

    $result = compareArrays($firstPacket, $secondPacket);
    if ($result === Status::continue) {
        throw new Exception('Continue Status found');
    }
    if ($result == Status::valid) {
        $validPairs[] = $pairIndex;
    }
    fprintf($fp, "Status: %s\n", $result->value);

    fprintf($fp, "\n");
}

fclose($fp);
//echo file_get_contents($outputPath);

var_dump(array_sum($validPairs));

function compareArrays(array $a, array $b, int $level = 0): Status
{
    global $fp;

    $indent = str_pad('', $level, "\t");
    fprintf($fp, "%s - Comparing %s\n", $indent, json_encode($a));
    $indent .= "\t\t";
    fprintf($fp, "%swith %s\n", $indent, json_encode($b));

    $indent .= "\t";

    $maxCount = max(count($a), count($b));

    for ($i = 0; $i < $maxCount; $i++) {
        $aExists = array_key_exists($i, $a);
        $bExists = array_key_exists($i, $b);

        if (!$aExists && $bExists) {
            fprintf($fp, "%sLeft has no more element\n", $indent);
            return Status::valid;
        }

        if ($aExists && !$bExists) {
            fprintf($fp, "%sRight has no more element\n", $indent);
            return Status::invalid;
        }

        $itemA = $a[$i];
        $itemB = $b[$i];

        if (!is_array($itemA) && !is_array($itemB)) {
            fprintf($fp, "%sComparing %d and %d\n", $indent, $itemA, $itemB);
            if ($itemA > $itemB) {
                fprintf($fp, "%sRight is smaller\n", $indent);
                return Status::invalid;
            } else if ($itemA < $itemB) {
                fprintf($fp, "%sLeft is smaller\n", $indent);
                return Status::valid;
            }
        } else if (is_array($itemA) && is_array($itemB)) {
            $newStatus = compareArrays($itemA, $itemB, $level + 1);
            if ($newStatus != Status::continue) {
                return $newStatus;
            }
        } else {
            if (is_array($itemA) && !is_array($itemB)) {
                fprintf($fp, "%sConverting %d to %s\n", $indent, $itemB, json_encode([$itemB]));
                $itemB = [$itemB];
            } else if (is_array($itemB) && !is_array($itemA)) {
                fprintf($fp, "%sConverting %d to %s\n", $indent, $itemA, json_encode([$itemA]));
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
