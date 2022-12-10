<?php
enum Direction: string
{
    case Up = 'U';
    case Right = 'R';
    case Down = 'D';
    case Left = 'L';
}

class Position
{
    public int $x = 0, $y = 0;

    public function moveTowards(Direction $dir)
    {
        switch ($dir) {
            case Direction::Up:
                $this->y++;
                break;
            case Direction::Down:
                $this->y--;
                break;
            case Direction::Right:
                $this->x++;
                break;
            case Direction::Left:
                $this->x--;
                break;
        }
    }

    public function catchUpWith(Position $otherPosition)
    {
        $rowDistance = abs($otherPosition->y - $this->y);
        $columnDistance = abs($otherPosition->x - $this->x);
        if ($rowDistance <= 1 && $columnDistance <= 1) {
            return;
        }

        if ($otherPosition->y == $this->y) {
            if ($this->x < $otherPosition->x) {
                $this->x = $otherPosition->x - 1;
            } else {
                $this->x = $otherPosition->x + 1;
            }
        } else if ($otherPosition->x == $this->x) {
            if ($this->y < $otherPosition->y) {
                $this->y = $otherPosition->y - 1;
            } else {
                $this->y = $otherPosition->y + 1;
            }
        } else {
            if ($rowDistance > $columnDistance) {
                $this->x += ($otherPosition->x - $this->x) / abs($otherPosition->x - $this->x);
                if ($this->y < $otherPosition->y) {
                    $this->y = $otherPosition->y - 1;
                } else {
                    $this->y = $otherPosition->y + 1;
                }
            } else {
                $this->y += ($otherPosition->y - $this->y) / abs($otherPosition->y - $this->y);
                if ($this->x < $otherPosition->x) {
                    $this->x = $otherPosition->x - 1;
                } else {
                    $this->x = $otherPosition->x + 1;
                }
            }
        }
    }

    public function __toString()
    {
        return "{$this->x}:{$this->y}";
    }
}
