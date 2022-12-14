<?php
$charset = array_combine(range('a', 'z'), range(0, 25));

enum Direction
{
    case left;
    case right;
    case up;
    case down;
}

class Coords
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }
}

class Map
{
    private array $tracedMap;
    private int $height;

    private int $iteration = 0;
    private int $dumpCount = 0;

    public function __construct(private string $map, private int $width)
    {
        $this->tracedMap = array_pad([], strlen($this->map), -1);
        $this->height = intval(strlen($map) / $this->width);
    }

    public function tracePath(): int
    {
        $startIndex = strpos($this->map, 'S');
        $endIndex = strpos($this->map, 'E');

        $this->tracedMap[$endIndex] = 0;
        $this->traceCell($endIndex, null);

        return ($startIndex === false) ? -1 : $this->getTraceValue($startIndex);
    }

    private function indexToCoords(int $index): Coords
    {
        $x = $index % $this->width;
        $y = intval($index / $this->width);
        return new Coords($x, $y);
    }

    private function coordsToIndex(int $x, int $y): int
    {
        return $y * $this->width + $x;
    }

    private function computeFillRate(): float
    {
        $total = count($this->tracedMap);
        $filled = 0;
        foreach ($this->tracedMap as $value) {
            if ($value >= 0) {
                $filled++;
            }
        }
        return $filled / $total;
    }

    private function checkCell(int $checkedIndex, int $fromIndex, Direction $from)
    {
        $fromValue = $this->getTraceValue($fromIndex);
        $checkedValue = $this->getTraceValue($checkedIndex);
        if ($checkedValue < 0 || $checkedValue > $fromValue + 1) {
            $fromElevation = $this->getCellElevation($fromIndex);
            $checkedElevation = $this->getCellElevation($checkedIndex);
            if ($checkedElevation >= $fromElevation - 1) {
                $this->tracedMap[$checkedIndex] = $fromValue + 1;
                $this->traceCell($checkedIndex, $from);
            }
        }
    }

    private function traceCell(int $index, ?Direction $from)
    {
        $this->iteration++;
        // if ($this->iteration % 100000 == 0) {
        //     printf("Iterations: %d\n", $this->iteration);
        //     printf("\tIndex: %d\n", $index);
        //     printf("\tMax Value: %d\n", max($this->tracedMap));
        //     printf("\tFill Rate: %0.2f %%\n", $this->computeFillRate() * 100);
        //     // $this->dumpTraceMap($index);
        //     // readline('Press Enter to continue...');
        // }

        $cellValue = $this->getTraceValue($index);
        $cellElevation = $this->getCellElevation($index);
        $cellCoords = $this->indexToCoords($index);

        // To the left
        if ($from != Direction::left && $cellCoords->x > 0) {
            $leftIndex = $this->coordsToIndex($cellCoords->x - 1, $cellCoords->y);
            $this->checkCell($leftIndex, $index, Direction::right);
        }

        // To the right
        if ($from != Direction::right && $cellCoords->x < $this->width - 1) {
            $rightIndex = $this->coordsToIndex($cellCoords->x + 1, $cellCoords->y);
            $this->checkCell($rightIndex, $index, Direction::left);
        }

        // Upwards
        if ($from != Direction::up && $cellCoords->y > 0) {
            $upIndex = $this->coordsToIndex($cellCoords->x, $cellCoords->y - 1);
            $this->checkCell($upIndex, $index, Direction::down);
        }

        // Downwards
        if ($from != Direction::down && $cellCoords->y < $this->height - 1) {
            $downIndex = $this->coordsToIndex($cellCoords->x, $cellCoords->y + 1);
            $this->checkCell($downIndex, $index, Direction::up);
        }
    }

    private function getCellElevation(int $index): int
    {
        global $charset;

        $cellValue = $this->map[$index];
        if ($cellValue == 'S') {
            return 0;
        } else if ($cellValue == 'E') {
            $cellValue = 'z';
        }
        return $charset[$cellValue];
    }

    public function getTraceValue($index): int
    {
        return $this->tracedMap[$index];
    }

    private function dumpTraceMap(int $currentTraceIndex)
    {
        $skipDump = 0;
        $maxDump = 1000;

        $this->dumpCount++;

        if ($this->dumpCount > $skipDump) {
            for ($y = 0; $y < $this->height; $y++) {
                for ($x = 0; $x < $this->width; $x++) {
                    $index = $this->coordsToIndex($x, $y);
                    if ($index == $currentTraceIndex) {
                        printf("X");
                    } else if ($this->tracedMap[$index] >= 0) {
                        printf($this->map[$index]);
                    } else {
                        printf('.');
                    }
                }
                printf("\n");
            }
            printf("\n");
        }

        if ($this->dumpCount == $maxDump) {
            exit();
        }
    }
}
