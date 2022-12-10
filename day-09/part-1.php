<?php

class Day9Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    const AXIS_X = 0;

    const AXIS_Y = 1;

    private array $tailVisitedCoordinates = [
        '0,0' => true,
    ];

    private array $knotCoordinates = [
        [0, 0], // Head
        [0, 0], // 1
    ];

    private int $headIndex = 0;

    private int $tailIndex;

    public function __construct()
    {
        $this->tailIndex = count($this->knotCoordinates) - 1;
    }

    public function solve(): void
    {
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            [$direction, $times] = explode(' ', $line);

            foreach (range(1, $times) as $time) {
                if ($direction === 'U') {
                    $this->knotCoordinates[$this->headIndex][self::AXIS_Y]++;
                } elseif ($direction === 'R') {
                    $this->knotCoordinates[$this->headIndex][self::AXIS_X]++;
                } elseif ($direction === 'D') {
                    $this->knotCoordinates[$this->headIndex][self::AXIS_Y]--;
                } elseif ($direction === 'L') {
                    $this->knotCoordinates[$this->headIndex][self::AXIS_X]--;
                }

                $this->moveKnotsToHead();
            }
        }

        echo "Tail visited count: " . count($this->tailVisitedCoordinates) . "\n";
    }

    private function moveKnotsToHead(): void
    {
        $knotsCount = count($this->knotCoordinates);

        for ($i = 1; $i < $knotsCount; $i++) {
            $currentKnotCoordinate = $this->knotCoordinates[$i];
            $previousKnotCoordinate = $this->knotCoordinates[$i - 1];

            $distance = $this->getCoordinateDistance(
                $currentKnotCoordinate[self::AXIS_X],
                $currentKnotCoordinate[self::AXIS_Y],
                $previousKnotCoordinate[self::AXIS_X],
                $previousKnotCoordinate[self::AXIS_Y],
            );

            if ($distance < 2) {
                continue;
            }

            $diffX = $previousKnotCoordinate[self::AXIS_X] - $currentKnotCoordinate[self::AXIS_X];
            $diffY = $previousKnotCoordinate[self::AXIS_Y] - $currentKnotCoordinate[self::AXIS_Y];

            $this->knotCoordinates[$i][self::AXIS_X] += gmp_sign($diffX);
            $this->knotCoordinates[$i][self::AXIS_Y] += gmp_sign($diffY);
        }

        // Keep track of the last tail coordination
        $tailCoordinateString = $this->coordinateArrayToString($this->knotCoordinates[$this->tailIndex]);

        $this->tailVisitedCoordinates[$tailCoordinateString] = true;
    }

    private function getCoordinateDistance(int $x1, int $y1, int $x2, int $y2): float
    {
        return sqrt((($x2 - $x1) ** 2) + (($y2 - $y1) ** 2));
    }

    private function coordinateArrayToString(array $coordinate): string
    {
        return implode(',', $coordinate);
    }
}

$problem = new Day9Part1();
$problem->solve();
