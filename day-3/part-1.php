<?php

class Day3Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    private array $charCounts = [];

    public function __construct()
    {
        $this->resetCharCount();
    }

    private function resetCharCount(): void
    {
        $this->charCounts = [];

        $ranges = [[
            'from' => 'a',
            'to' => 'z',
        ], [
            'from' => 'A',
            'to' => 'Z',
        ]];

        foreach ($ranges as $range) {
            $current = ord($range['from']);
            $end = ord($range['to']);

            while ($current <= $end) {
                $this->charCounts[chr($current)] = 0;
                $current++;
            }
        }
    }

    public function solve(): void
    {
        $sum = 0;
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        $lineCount = 0;

        while (($line = fgets($file)) !== false) {
            $lineCount++;
            $line = trim($line);

            $this->resetCharCount();

            $characters = str_split($line);
            $halfPoint = count($characters) / 2;
            $firstHalf = array_unique(array_slice($characters, 0, $halfPoint));
            $secondHalf = array_unique(array_slice($characters, $halfPoint));

            $chars = [
                ...$firstHalf,
                ...$secondHalf,
            ];

            foreach ($chars as $char) {
                $this->charCounts[$char]++;
            }

            foreach ($this->charCounts as $character => $charCount) {
                if ($charCount !== 2) {
                    continue;
                }

                $priority = $this->getPriority($character);

                echo "Line: $lineCount - $line, Character: $character, Priority: $priority\n";

                $sum += $priority;
            }
        }

        echo "Sum: $sum\n";
    }

    private function getPriority(string $character): int
    {
        if (ctype_lower($character)) {
            return ord($character) - ord('a') + 1;
        }

        return ord($character) - ord('A') + 27;
    }
}

$problem = new Day3Part1();
$problem->solve();

