<?php
class Point
{
    public int $x;
    public int $y;

    public function __construct(string|Point $point)
    {
        if (is_string($point)) {
            list($x, $y) = explode(',', $point);
            $this->x = $x;
            $this->y = $y;
        } else {
            $this->x = $point->x;
            $this->y = $point->y;
        }
    }

    public function __toString()
    {
        return self::pointToString($this->x, $this->y);
    }

    public static function pointToString(int $x, int $y): string
    {
        return "{$x},{$y}";
    }
}

enum CellType: string
{
    case empty = '.';
    case wall = '#';
    case sand = 'o';
}

class Map
{
    private array $blockedIndices = [];

    private int $minX;
    private int $maxX;

    public function __construct(private readonly int $maxY, private bool $part2 = false)
    {
        $this->minX = getrandmax();
        $this->maxX = -getrandmax();
    }

    private function getCellType(int $x, int $y): CellType
    {
        if ($this->part2 && $y == $this->maxY) {
            return CellType::wall;
        }

        $index = Point::pointToString($x, $y);
        if (!array_key_exists($index, $this->blockedIndices)) {
            return CellType::empty;
        }
        return $this->blockedIndices[$index];
    }

    private function isBlocked($x, $y): bool
    {
        return $this->getCellType($x, $y) != CellType::empty;
    }

    public function dumpGrid($stream)
    {
        for ($y = 0; $y <= $this->maxY; $y++) {
            for ($x = $this->minX; $x <= $this->maxX; $x++) {
                fprintf($stream, "%s", $this->getCellType($x, $y)->value);
            }
            fprintf($stream, "\n");
        }
    }

    public function drawWall(Point $from, Point $to)
    {
        if ($from->x != $to->x && $from->y != $to->y) {
            throw new Exception("Lines must be either horizontal or vertical");
        }

        if ($from->x == $to->x) {
            $startY = min($from->y, $to->y);
            $endY = max($from->y, $to->y);
            for ($y = $startY; $y <= $endY; $y++) {
                $index = Point::pointToString($from->x, $y);
                $this->blockedIndices[$index] = CellType::wall;
            }
            $this->minX = min($this->minX, $from->x);
            $this->maxX = max($this->maxX, $from->x);
        } else {
            $startX = min($from->x, $to->x);
            $endX = max($from->x, $to->x);
            for ($x = $startX; $x <= $endX; $x++) {
                $index = Point::pointToString($x, $from->y);
                $this->blockedIndices[$index] = CellType::wall;;
            }
            $this->minX = min($this->minX, $startX);
            $this->maxX = max($this->maxX, $endX);
        }
    }

    public function dropSand(Point $from): bool
    {
        $sandPos = new Point($from);
        if ($this->part2 && $this->isBlocked($sandPos->x, $sandPos->y)) {
            return false;
        }
        while (true) {
            if ($sandPos->y + 1 > $this->maxY) {
                return false;
            }
            if ($this->isBlocked($sandPos->x, $sandPos->y + 1)) {
                if (!$this->part2 && $sandPos->x == $this->minX) {
                    return false;
                }
                $cellTypeLeft = $this->getCellType($sandPos->x - 1, $sandPos->y + 1);
                if ($cellTypeLeft == CellType::empty) {
                    $sandPos->x--;
                } else {
                    if (!$this->part2 && $sandPos->x + 1 > $this->maxX) {
                        return false;
                    }
                    $cellTypeRight = $this->getCellType($sandPos->x + 1, $sandPos->y + 1);
                    if ($cellTypeRight == CellType::empty) {
                        $sandPos->x++;
                    } else {
                        $index = Point::pointToString($sandPos->x, $sandPos->y);
                        $this->blockedIndices[$index] = CellType::sand;
                        return true;
                    }
                }
            }
            $sandPos->y++;
        }
        return false;
    }
}
