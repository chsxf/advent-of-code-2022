<?php
class Coords
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }

    public function manhattanDistance(int $fromX, int $fromY): int
    {
        return abs($this->x - $fromX) + abs($this->y - $fromY);
    }
}

class Sensor
{
    private readonly int $manhattanDistance;

    public readonly int $inclusiveMinX;
    public readonly int $inclusiveMaxX;
    public readonly int $inclusiveMinY;
    public readonly int $inclusiveMaxY;

    public function __construct(public readonly Coords $position, public readonly Coords $closestBeacon)
    {
        $this->manhattanDistance = $position->manhattanDistance($closestBeacon->x, $closestBeacon->y);

        $this->inclusiveMinX = $position->x - $this->manhattanDistance;
        $this->inclusiveMaxX = $position->x + $this->manhattanDistance;

        $this->inclusiveMinY = $position->y - $this->manhattanDistance;
        $this->inclusiveMaxY = $position->y + $this->manhattanDistance;
    }

    public function isPositionInRange(int $x, int $y): bool
    {
        if ($x < $this->inclusiveMinX || $x > $this->inclusiveMaxX || $y < $this->inclusiveMinY || $y > $this->inclusiveMaxY) {
            return false;
        }
        return $this->position->manhattanDistance($x, $y) <= $this->manhattanDistance;
    }

    public function horizontalMaxRangeLimit(int $y): int
    {
        $base = abs($this->position->y - $y);
        $remainder = $this->manhattanDistance - $base;
        return $this->position->x + $remainder;
    }
}
