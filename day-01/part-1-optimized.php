<?php

// The optimized way for part-1 problem, instead of fetching the whole file and chunk
// we'll loop through it line by line, and use the empty line as a split point for the chunk
// in each chunk, we sum the amounts and check against the current max
// by the end of the fetching line loop, we'll have the max already

$file = fopen(__DIR__ . '/input.txt', 'r');

$max = 0;
$currentChunkCount = 0;

while (($line = fgets($file)) !== false) {
    if (empty(trim($line))) {
        if ($currentChunkCount > $max) {
            $max = $currentChunkCount;
        }
        $currentChunkCount = 0;
        continue;
    }

    $currentChunkCount += (int) $line;
}

fclose($file);

echo $max . "\n";
