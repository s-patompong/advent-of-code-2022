<?php

function isVisibleFromTheTop(int $row, int $column, array $trees): bool
{
    $treeHeight = $trees[$row][$column];
    $toRow = $row - 1;

    // Stay at the same column, loop from row = 0 to row = $row - 1
    for ($currentRow = 0; $currentRow <= $toRow; $currentRow++) {
        $currentTreeHeight = $trees[$currentRow][$column];

        if ($currentTreeHeight >= $treeHeight) {
            return false;
        }
    }

    return true;
}

function isVisibleFromTheRight(int $row, int $column, array $trees): bool
{
    $treeHeight = $trees[$row][$column];
    $toColumn = count($trees[0]) - 1;

    // Stay at the same row, loop from column = $column+1 to column = $maxColumn
    for ($currentColumn = $column + 1; $currentColumn <= $toColumn; $currentColumn++) {
        $currentTreeHeight = $trees[$row][$currentColumn];

        if ($currentTreeHeight >= $treeHeight) {
            return false;
        }
    }

    return true;
}

function isVisibleFromTheBottom(int $row, int $column, array $trees): bool
{
    $treeHeight = $trees[$row][$column];
    $toRow = count($trees) - 1;

    // Stay at the same column, loop from row = $row+1 to $row = $maxRow
    for ($currentRow = $row + 1; $currentRow <= $toRow; $currentRow++) {
        $currentTreeHeight = $trees[$currentRow][$column];

        if ($currentTreeHeight >= $treeHeight) {
            return false;
        }
    }

    return true;
}

function isVisibleFromTheLeft(int $row, int $column, array $trees): bool
{
    $treeHeight = $trees[$row][$column];
    $toColumn = $column - 1;

    // Stay at the same row, loop from column = 0 to column = $column - 1
    for ($currentColumn = 0; $currentColumn <= $toColumn; $currentColumn++) {
        $currentTreeHeight = $trees[$row][$currentColumn];

        if ($currentTreeHeight >= $treeHeight) {
            return false;
        }
    }

    return true;
}

function isOnTheEdge(int $row, int $column, array $trees): bool
{
    return $row === 0
        || $column === 0
        || $row === (count($trees) - 1)
        || $column === (count($trees[0]) - 1);
}

function isVisible(int $row, int $column, array $trees): bool
{
    if (isOnTheEdge($row, $column, $trees)) {
        return true;
    }

    return isVisibleFromTheTop($row, $column, $trees)
        || isVisibleFromTheRight($row, $column, $trees)
        || isVisibleFromTheBottom($row, $column, $trees)
        || isVisibleFromTheLeft($row, $column, $trees);
}

$file = fopen(__DIR__ . '/input.txt', 'r');

$trees = [];

while (($line = fgets($file)) !== false) {
    $trees[] = str_split(trim($line));
}

fclose($file);

$countVisible = 0;

$rowCount = count($trees);
$columnCount = count($trees[0]);

for ($row = 0; $row < $rowCount; $row++) {
    for ($column = 0; $column < $columnCount; $column++) {
        if (isVisible($row, $column, $trees)) {
            $countVisible++;
        }
    }
}

echo "Visible: $countVisible\n";
