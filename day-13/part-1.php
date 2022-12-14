<?php

class Pair
{
    public function isInCorrectOrder(array $left, array $right): ?bool
    {
        for ($i = 0; $i < count($left); $i++) {
            // If the right list runs out of items first, the inputs are not in the right order
            if (!isset($right[$i])) {
                return false;
            }

            $leftValue = $left[$i];
            $rightValue = $right[$i];

            // Both are integer, compare their values
            if (is_int($leftValue) && is_int($rightValue)) {
                if ($leftValue === $rightValue) {
                    continue;
                }

                if ($leftValue < $rightValue) {
                    return true;
                }

                return false;
            }

            if (is_int($leftValue)) {
                $leftValue = [$leftValue];
            }

            if (is_int($rightValue)) {
                $rightValue = [$rightValue];
            }

            $isInCorrectOrder = $this->isInCorrectOrder($leftValue, $rightValue);

            if ($isInCorrectOrder !== null) {
                return $isInCorrectOrder;
            }
        }

        // If the left list runs out of items first, the inputs are in the right order
        if (count($right) > count($left)) {
            return true;
        }

        // Otherwise everything is in order, we return null so the code can keep moving
        return null;
    }
}

class Day13Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    /**
     * @var array<int, Pair>
     */
    private array $pairs = [];

    public function solve(): void
    {
        $allPairs = explode("\n\n", file_get_contents(__DIR__ . '/' . self::INPUT_FILE_NAME));

        foreach ($allPairs as $pairs) {
            [$left, $right] = explode("\n", $pairs);

            $this->pairs[] = [
                'left' => json_decode($left, true),
                'right' => json_decode($right, true),
            ];
        }

        $count = 0;

        foreach ($this->pairs as $index => $pair) {
            $isInCorrectOrder = (new Pair())->isInCorrectOrder($pair['left'], $pair['right']);

            if ($isInCorrectOrder === null) {
                $isInCorrectOrder = true;
            }

            if ($isInCorrectOrder) {
                $count += ($index + 1);
            }
        }

        echo "Answer: $count\n";
    }
}

$problem = new Day13Part1();
$problem->solve();
