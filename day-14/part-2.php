<?php

class MinMaxFromMapFinder
{
    public int $minX = PHP_INT_MAX;

    public int $maxX = 0;

    public int $minY = PHP_INT_MAX;

    public int $maxY = 0;

    public function parseLine(string $line): void
    {
        $coordinates = explode(' -> ', $line);

        foreach ($coordinates as $coordinate) {
            [$x, $y] = explode(',', $coordinate);

            $x = (int) $x;
            $y = (int) $y;

            $this->minX = min($x, $this->minX);
            $this->maxX = max($x, $this->maxX);

            $this->minY = min($y, $this->minY);
            $this->maxY = max($y, $this->maxY);
        }
    }

    public function toArray(): array
    {
        return [
            'min_x' => $this->minX,
            'max_x' => $this->maxX,
            'min_y' => $this->minY,
            'max_y' => $this->maxY,
        ];
    }
}

class SandDropSimulator
{
    const AIR = '.';

    const STONE = '#';

    const SAND = 'O';

    public array $grid = [];

    public int $sandOriginColumn;

    public int $restedSandCount = 0;

    private ?int $currentSandRow = null;

    private ?int $currentSandColumn = null;

    public function __construct(
        public int $minX,
        public int $maxX,
        public int $minY,
        public int $maxY,
    )
    {
        $this->maxY += 2;
        $this->minX -= ($this->maxY * 2);
        $this->maxX += ($this->maxY * 2);

        $this->buildAirGrid();

        $this->sandOriginColumn = 500 - $this->minX;

        $this->grid[0][$this->sandOriginColumn] = '+';
    }

    public function parseLine(string $line): void
    {
        $coordinates = explode(' -> ', $line);

        for ($i = 0; $i < count($coordinates) - 1; $i++) {
            [$fromX, $fromY] = explode(',', $coordinates[$i]);
            [$toX, $toY] = explode(',', $coordinates[$i + 1]);

            $fromX = $fromX - $this->minX;
            $toX = $toX - $this->minX;

            if ($fromX !== $toX) {
                $minColumn = min($fromX, $toX);
                $maxColumn = max($fromX, $toX);

                for ($j = $minColumn; $j <= $maxColumn; $j++) {
                    $this->grid[$fromY][$j] = self::STONE;
                }
                continue;
            }

            if ($fromY !== $toY) {
                $minRow = min($fromY, $toY);
                $maxRow = max($fromY, $toY);

                for ($j = $minRow; $j <= $maxRow; $j++) {
                    $this->grid[$j][$fromX] = self::STONE;
                }
            }
        }
    }

    private function buildAirGrid(): void
    {
        for ($row = 0; $row <= $this->maxY; $row++) {
            $currentRow = [];

            for ($column = $this->minX; $column <= $this->maxX; $column++) {
                $currentRow[] = self::AIR;
            }

            $this->grid[] = $currentRow;
        }

        $toColumn = $this->maxX - $this->minX;

        for ($column = 0; $column <= $toColumn; $column++) {
            $this->grid[$this->maxY][$column] = self::STONE;
        }
    }

    public function printGrid(): void
    {
        foreach ($this->grid as $rowIndex => $row) {
            foreach ($row as $columnIndex => $column) {
                // Print C for the current sand position
                if ($rowIndex === $this->currentSandRow && $columnIndex === $this->currentSandColumn) {
                    echo self::SAND;
                    continue;
                }

                echo $column;
            }

            echo "\n";
        }
    }

    public function simulateUntilSandIsFull(): void
    {
        $continue = true;

        do {
            try {
                $this->moveCurrentSand();
            } catch (Exception) {
                $continue = false;
            }
        } while ($continue);
    }

    /**
     * @throws Exception
     */
    private function moveCurrentSand(): void
    {
        // If no sand being spawned yet, start by spawning it
        if ($this->currentSandRow === null && $this->currentSandColumn === null) {
            $this->currentSandRow = 0;
            $this->currentSandColumn = $this->sandOriginColumn;
            return;
        }

        // If below is nothing (bottom is air), then throw an exception
        if (!isset($this->grid[$this->currentSandRow + 1][$this->currentSandColumn])) {
            throw new Exception("Out of bound!");
        }

        // If the below row is air, we move the current sand down one step
        if ($this->grid[$this->currentSandRow + 1][$this->currentSandColumn] === self::AIR) {
            $this->currentSandRow++;
            return;
        }


        // If the left side is the abyss, then throw an exception
        if (!isset($this->grid[$this->currentSandRow + 1][$this->currentSandColumn - 1])) {
            throw new Exception("Out of bound!");
        }

        // Below is not air, try move to the left if possible
        if ($this->grid[$this->currentSandRow + 1][$this->currentSandColumn - 1] === self::AIR) {
            $this->currentSandRow++;
            $this->currentSandColumn--;
            return;
        }

        // If the right side is the abyss, then throw an exception
        if (!isset($this->grid[$this->currentSandRow + 1][$this->currentSandColumn + 1])) {
            throw new Exception("Out of bound!");
        }

        // Below is not air, try move to the right if possible
        if ($this->grid[$this->currentSandRow + 1][$this->currentSandColumn + 1] === self::AIR) {
            $this->currentSandRow++;
            $this->currentSandColumn++;
            return;
        }

        // Stuck, can't move anymore, add this position as sand and reset the current sand position
        $this->grid[$this->currentSandRow][$this->currentSandColumn] = self::SAND;
        $this->restedSandCount++;
        if ($this->currentSandRow === 0) {
            throw new Exception("Grid is full!");
        }

        $this->currentSandRow = null;
        $this->currentSandColumn = null;
    }
}

class Day14Part2
{
    const INPUT_FILE_NAME = 'input.txt';

    public function solve(): void
    {
        $minMaxFinder = new MinMaxFromMapFinder();

        $lines = [];
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            $lines[] = $line;

            $minMaxFinder->parseLine($line);
        }

        $simulator = new SandDropSimulator(
            minX: $minMaxFinder->minX,
            maxX: $minMaxFinder->maxX,
            minY: $minMaxFinder->minY,
            maxY: $minMaxFinder->maxY,
        );

        foreach ($lines as $line) {
            $simulator->parseLine($line);
        }

        $simulator->simulateUntilSandIsFull();

        echo "Answer: $simulator->restedSandCount\n";
    }
}

$problem = new Day14Part2();
$problem->solve();
