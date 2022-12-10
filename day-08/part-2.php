<?php

function topScenicScore(int $row, int $column, array $trees): int
{
    // If it's at row = 0, top scenic score is 0 because there is no top tree
    if ($row === 0) {
        return 0;
    }

    $treeHeight = $trees[$row][$column];
    $scenicScore = 0;

    // Stay in the same column, row start at $row - 1 and ends at 0, -- each time
    for ($currentRow = $row - 1; $currentRow >= 0; $currentRow--) {
        $scenicScore++;

        $currentTreeHeight = $trees[$currentRow][$column];

        if ($currentTreeHeight >= $treeHeight) {
            return $scenicScore;
        }
    }

    return $scenicScore;
}

function bottomScenicScore(int $row, int $column, array $trees): int
{
    $lastRow = count($trees) - 1;

    // If it's at the last row, bottom scenic score is 0 because there is no bottom tree
    if ($row === $lastRow) {
        return 0;
    }

    $treeHeight = $trees[$row][$column];
    $scenicScore = 0;

    // Stay in the same column, row start at $row + 1 and ends at last row
    for ($currentRow = $row + 1; $currentRow <= $lastRow; $currentRow++) {
        $scenicScore++;

        $currentTreeHeight = $trees[$currentRow][$column];

        if ($currentTreeHeight >= $treeHeight) {
            break;
        }
    }

    return $scenicScore;
}

function rightScenicScore(int $row, int $column, array $trees): int
{
    $lastColumn = count($trees[0]) - 1;

    // If it's at the last column, right scenic score is 0 because there is no right tree
    if ($column === $lastColumn) {
        return 0;
    }

    $treeHeight = $trees[$row][$column];
    $scenicScore = 0;

    // Stay in the same row, column start at $column + 1 and ends at last column
    for ($currentColumn = $column + 1; $currentColumn <= $lastColumn; $currentColumn++) {
        $scenicScore++;

        $currentTreeHeight = $trees[$row][$currentColumn];

        if ($currentTreeHeight >= $treeHeight) {
            break;
        }
    }

    return $scenicScore;
}

function leftScenicScore(int $row, int $column, array $trees): int
{
    // If it's at the first column, left scenic score is 0 because there is no left tree
    if ($column === 0) {
        return 0;
    }

    $treeHeight = $trees[$row][$column];
    $scenicScore = 0;

    // Stay in the same row, column start at $column - 1 and ends at 0, -- each time
    for ($currentColumn = $column - 1; $currentColumn >= 0; $currentColumn--) {
        $scenicScore++;

        $currentTreeHeight = $trees[$row][$currentColumn];

        if ($currentTreeHeight >= $treeHeight) {
            break;
        }
    }

    return $scenicScore;
}

function getScenicScore(int $row, int $column, array $trees): int
{
    $topScenicScore = topScenicScore($row, $column, $trees);
    $rightScenicScore = rightScenicScore($row, $column, $trees);
    $bottomScenicScore = bottomScenicScore($row, $column, $trees);
    $leftScenicScore = leftScenicScore($row, $column, $trees);

    return $topScenicScore
        * $rightScenicScore
        * $bottomScenicScore
        * $leftScenicScore;
}

$file = fopen(__DIR__ . '/input.txt', 'r');

$trees = [];

while (($line = fgets($file)) !== false) {
    $trees[] = str_split(trim($line));
}

fclose($file);

$maxScenicScore = 0;

$rowCount = count($trees);
$columnCount = count($trees[0]);

for ($row = 0; $row < $rowCount; $row++) {
    for ($column = 0; $column < $columnCount; $column++) {
        $scenicScore = getScenicScore($row, $column, $trees);

        $maxScenicScore = max($scenicScore, $maxScenicScore);
    }
}

echo "Max scenic score: $maxScenicScore\n";
