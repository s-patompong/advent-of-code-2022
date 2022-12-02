<?php

$content = file_get_contents(__DIR__ . '/input.txt');

$chunks = explode("\n\n", $content);

$sums = [];

foreach ($chunks as $chunk) {
    $sums[] = array_sum(explode("\n", $chunk));
}

rsort($sums);

echo ($sums[0] + $sums[1] + $sums[2]) . "\n";



