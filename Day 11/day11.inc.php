<?php
interface IOperation
{
    public function apply(int $value): int;
}

class AddOperation implements IOperation
{
    public function __construct(private int $valueToAdd)
    {
    }

    public function apply(int $value): int
    {
        return $value + $this->valueToAdd;
    }
}

class MultiplyOperation implements IOperation
{
    public function __construct(private int $multiplier)
    {
    }

    public function apply(int $value): int
    {
        return $value * $this->multiplier;
    }
}

class PowerOperation implements IOperation
{
    public function apply(int $value): int
    {
        return $value * $value;
    }
}

class Monkey
{
    private int $inspectionCount;
    private array $items;

    function __construct(
        array $startingItems,
        public readonly IOperation $operation,
        public readonly int $divider,
        public readonly int $trueDestination,
        public readonly int $falseDestination
    ) {
        $this->items = $startingItems;
        $this->inspectionCount = 0;
    }

    public function getInspectionCount()
    {
        return $this->inspectionCount;
    }

    public function inspect(): ?int
    {
        if (empty($this->items)) {
            return null;
        }
        $this->inspectionCount++;
        return array_shift($this->items);
    }

    public function push(int $value)
    {
        array_push($this->items, $value);
    }
}
