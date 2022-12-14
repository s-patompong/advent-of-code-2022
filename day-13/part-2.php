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

class Day13Part2
{
    const INPUT_FILE_NAME = 'input.txt';

    public function solve(): void
    {
        $dividerPackets = [
            "[[2]]" => [[2]],
            "[[6]]" => [[6]],
        ];

        $packets = [];

        $allPairs = explode("\n\n", file_get_contents(__DIR__ . '/' . self::INPUT_FILE_NAME));

        foreach ($allPairs as $pairs) {
            [$left, $right] = explode("\n", $pairs);

            $packets[] = json_decode($left, true);
            $packets[] = json_decode($right, true);
        }

        $packets[] = $dividerPackets['[[2]]'];
        $packets[] = $dividerPackets['[[6]]'];

        for ($i = 0; $i < count($packets); $i++) {
            for ($j = 0; $j < count($packets); $j++) {
                $left = $packets[$i];
                $right = $packets[$j];

                $isInCorrectOrder = (new Pair())->isInCorrectOrder($right, $left);

                if ($isInCorrectOrder === null) {
                    $isInCorrectOrder = true;
                }

                // Do nothing if the pair is in the correct order already
                if ($isInCorrectOrder) {
                    continue;
                }

                $packets[$i] = $right;
                $packets[$j] = $left;
            }
        }

        $dividerPacketsLocation = [];

        foreach ($packets as $index => $packet) {
            $packetString = json_encode($packet);

            echo "$packetString\n";

            if (array_key_exists($packetString, $dividerPackets)) {
                $dividerPacketsLocation[] = ($index + 1);
            }
        }

        print_r($dividerPacketsLocation);

        $answer = array_reduce($dividerPacketsLocation, fn(int $total, int $curr) => $total * $curr, 1);

        echo "Answer: $answer\n";
    }
}

$problem = new Day13Part2();
$problem->solve();

