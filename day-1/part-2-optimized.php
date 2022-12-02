<?php

// The optimized way for part-2 problem, instead of fetching the whole file and chunk
// we'll loop through it line by line, and use the empty line as a split point for the chunk
// in each chunk, we sum the amounts store the sum in the sums array.
//
// At the end of the fetching line loop, we will reverse sort the sum to bring the largest sum
// at the front of the array, and then sum the first three

$file = fopen(__DIR__ . '/input.txt', 'r');

$sums = [];
$currentChunkCount = 0;

while (($line = fgets($file)) !== false) {
    if (empty(trim($line))) {
        $sums[] = $currentChunkCount;
        $currentChunkCount = 0;
        continue;
    }

    $currentChunkCount += (int) $line;
}

fclose($file);

rsort($sums);

echo ($sums[0] + $sums[1] + $sums[2]) . "\n";
