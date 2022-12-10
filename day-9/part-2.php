<?php

class Day9Part2
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
        [0, 0], // 2
        [0, 0], // 3
        [0, 0], // 4
        [0, 0], // 5
        [0, 0], // 6
        [0, 0], // 7
        [0, 0], // 8
        [0, 0], // 9
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

            $knotStringCoordinates = $this->getDiagonalCoordinates($currentKnotCoordinate);
            $previousKnotStringCoordinates = $this->coordinateArrayToString($previousKnotCoordinate);

            // Do nothing if it's already in the diagonal area or overlapping
            if (array_key_exists($previousKnotStringCoordinates, $knotStringCoordinates)) {
                continue;
            }

            $diffX = $previousKnotCoordinate[self::AXIS_X] - $currentKnotCoordinate[self::AXIS_X];
            $diffY = $previousKnotCoordinate[self::AXIS_Y] - $currentKnotCoordinate[self::AXIS_Y];

            // If it's in the coordinate (diffX = 0 and diffY = 0) or diagonal
            // then we don't want to move it
            // if (abs($diffX) <= 1 && abs($diffY <= 1)) {
            //     continue;
            // }

            $this->knotCoordinates[$i][self::AXIS_X] += gmp_sign($diffX);
            $this->knotCoordinates[$i][self::AXIS_Y] += gmp_sign($diffY);

            // If in the same row (Y Axis), move current knot toward the previous knot's position
            // if ($knotCoordinate[self::AXIS_Y] === $previousKnotCoordinate[self::AXIS_Y]) {
            //     $diffX = $previousKnotCoordinate[self::AXIS_X] - $knotCoordinate[self::AXIS_X];
            //
            //     // If Y is in the minus zone, the
            //     $this->knotCoordinates[$i][self::AXIS_X] = $this->knotCoordinates[$i][self::AXIS_X] + gmp_sign($diffX);
            // }
            //
            // // If in the same column (X Axis), move current knot toward previous knot's position
            // if ($knotCoordinate[self::AXIS_X] === $previousKnotCoordinate[self::AXIS_X]) {
            //     $diffY = $previousKnotCoordinate[self::AXIS_Y] - $knotCoordinate[self::AXIS_Y];
            //
            //     // If Y is in the minus zone, the
            //     $this->knotCoordinates[$i][self::AXIS_Y] = $this->knotCoordinates[$i][self::AXIS_Y] + gmp_sign($diffY);
            // }

            // Different row, different column, move diagonally
            // $diffX = $previousKnotCoordinate[self]
        }

        // Keep track of the last tail coordination
        $tailCoordinateString = $this->coordinateArrayToString($this->knotCoordinates[$this->tailIndex]);

        $this->tailVisitedCoordinates[$tailCoordinateString] = true;
    }

    private function getDiagonalCoordinates(array $centerCoordinate): array
    {
        return [
            // The current coordinate
            $this->coordinateArrayToString($centerCoordinate) => true,

            // The row above the current coordinate
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] - 1, $centerCoordinate[self::AXIS_Y] + 1]) => true,
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X], $centerCoordinate[self::AXIS_Y] + 1]) => true,
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] + 1, $centerCoordinate[self::AXIS_Y] + 1]) => true,

            // The current coordinate row
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] - 1, $centerCoordinate[self::AXIS_Y]]) => true,
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] + 1, $centerCoordinate[self::AXIS_Y]]) => true,

            // The row below the current coordinate
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] - 1, $centerCoordinate[self::AXIS_Y] - 1]) => true,
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X], $centerCoordinate[self::AXIS_Y] - 1]) => true,
            $this->coordinateArrayToString([$centerCoordinate[self::AXIS_X] + 1, $centerCoordinate[self::AXIS_Y] - 1]) => true,
        ];
    }

    private function coordinateArrayToString(array $coordinate): string
    {
        return implode(',', $coordinate);
    }
}

$problem = new Day9Part2();
$problem->solve();
