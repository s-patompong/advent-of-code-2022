<?php

class Day3Part2
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

        $groupCount = 0;
        $currentGroup = [];

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            $characters = array_unique(str_split($line));

            $currentGroup[] = $characters;

            if (count($currentGroup) === 3) {
                $groupCount++;

                foreach ($currentGroup as $chars) {
                    foreach ($chars as $character) {
                        $this->charCounts[$character]++;
                    }
                }

                foreach ($this->charCounts as $character => $charCount) {
                    if ($charCount !== 3) {
                        continue;
                    }

                    $priority = $this->getPriority($character);

                    echo "Group: $groupCount, Character: $character, Priority: $priority\n";

                    $sum += $priority;
                }

                $currentGroup = [];
            }

            $this->resetCharCount();
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

$problem = new Day3Part2();
$problem->solve();

