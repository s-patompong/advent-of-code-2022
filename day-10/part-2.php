<?php

class Day10Part2
{
    const INPUT_FILE_NAME = 'input.txt';

    const INSTRUCTION_NOOP = 'noop';

    const CRT_ROW_MAX_CYCLE = 40;

    private array $processedCycles = [[
        'initialized' => 1,
        'executed' => 1,
    ]];

    private int $currentCRTDrawRow = 0;

    private int $currentCRTDrawPositionInCurrentRow = 0;

    private array $monitor = [
        '........................................',
        '........................................',
        '........................................',
        '........................................',
        '........................................',
        '........................................',
    ];

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

        $processedCycleCount = count($this->processedCycles) - 1;

        for ($i = 1; $i < $processedCycleCount; $i++) {
            $processedCycle = $this->processedCycles[$i];

            $x = $processedCycle['initialized'] % self::CRT_ROW_MAX_CYCLE;

            $spritePositions = [$x - 1, $x, $x + 1];

            // Change the current CRT position to # if at least one of the sprint position
            // is overlapping with the current CRT position
            if (in_array($this->currentCRTDrawPositionInCurrentRow, $spritePositions)) {
                $this->monitor[$this->currentCRTDrawRow][$this->currentCRTDrawPositionInCurrentRow] = '#';
            }

            // Move CRT position to the next character
            $this->currentCRTDrawPositionInCurrentRow++;

            // Move drawing to the next row if the current pointer is out of bound
            if (!isset($this->monitor[$this->currentCRTDrawRow][$this->currentCRTDrawPositionInCurrentRow])) {
                $this->currentCRTDrawRow++;
                $this->currentCRTDrawPositionInCurrentRow = 0;
            }
        }

        $this->printMonitor();
    }

    private function printMonitor(): void
    {
        foreach ($this->monitor as $line) {
            echo "$line\n";
        }
    }
}

$problem = new Day10Part2();
$problem->solve();
