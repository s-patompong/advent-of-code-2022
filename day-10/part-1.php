<?php

class Day10Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    const INSTRUCTION_NOOP = 'noop';

    private array $processedCycles = [[
        'initialized' => 1,
        'executed' => 1,
    ]];

    private array $targetCycles = [20, 60, 100, 140, 180, 220];

    public function solve(): void
    {
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            $x = $this->processedCycles[count($this->processedCycles) - 1]['executed'];

            $this->processedCycles[] = [
                'initialized' => $x,
                'executed' => $x,
            ];

            if ($line === self::INSTRUCTION_NOOP) {
                continue;
            }

            // For now, we'll just ignore the instruction
            [, $value] = explode(' ', $line);

            $this->processedCycles[] = [
                'initialized' => $x,
                'executed' => $x + (int) $value,
            ];
        }

        $totalStrength = array_reduce($this->targetCycles, function(int $total, int $targetCycle) {
            return $total + ($this->processedCycles[$targetCycle]['initialized'] * $targetCycle);
        }, 0);

        echo "Total Strength: $totalStrength\n";
    }
}

$problem = new Day10Part1();
$problem->solve();
