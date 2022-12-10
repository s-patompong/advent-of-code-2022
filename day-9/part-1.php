<?php

class Day9Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    const AXIS_X = 0;

    const AXIS_Y = 1;

    private array $tailVisitedCoordinates = [
        '0,0' => true,
    ];

    private array $currentHeadCoordinate = [0, 0];

    private array $currentTailCoordinate = [0, 0];

    private array $previousHeadCoordinate = [0, 0];

    public function solve(): void
    {
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            [$direction, $times] = explode(' ', $line);

            foreach (range(1, $times) as $time) {
                $this->previousHeadCoordinate = $this->currentHeadCoordinate;

                if ($direction === 'U') {
                    $this->currentHeadCoordinate[self::AXIS_Y]++;
                } elseif ($direction === 'R') {
                    $this->currentHeadCoordinate[self::AXIS_X]++;
                } elseif ($direction === 'D') {
                    $this->currentHeadCoordinate[self::AXIS_Y]--;
                } elseif ($direction === 'L') {
                    $this->currentHeadCoordinate[self::AXIS_X]--;
                }

                $this->moveTailToFollowHead();
            }
        }

        echo "Tail visited count: " . count($this->tailVisitedCoordinates) . "\n";
    }

    private function moveTailToFollowHead(): void
    {
        $tailDiagonalCoordinates = $this->getDiagonalCoordinates($this->currentTailCoordinate);

        $headCoordinateString = $this->coordinateArrayToString($this->currentHeadCoordinate);

        // No need to move tail if it's already diagonal to head
        if (array_key_exists($headCoordinateString, $tailDiagonalCoordinates)) {
            return;
        }

        // Move tail to previous head coordinate if they're now not diagonal to each other
        $this->currentTailCoordinate = $this->previousHeadCoordinate;

        $tailCoordinateString = $this->coordinateArrayToString($this->currentTailCoordinate);

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

$problem = new Day9Part1();
$problem->solve();
