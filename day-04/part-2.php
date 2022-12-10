<?php

$total = 0;

$file = fopen(__DIR__ . '/input.txt', 'r');

while (($line = fgets($file)) !== false) {
    $line = trim($line);

    $pairs = explode(',', $line);

    [$range1From, $range1To] = explode('-', $pairs[0]);
    [$range2From, $range2To] = explode('-', $pairs[1]);

    $range1From = (int) $range1From;
    $range1To = (int) $range1To;
    $range2From = (int) $range2From;
    $range2To = (int) $range2To;

    $range2FromOverlapRange1 = $range1From <= $range2From && $range1To >= $range2From;
    $range2ToOverlapRange1 = $range1From <= $range2To && $range1To >= $range2To;

    $range1FromOverlapRange2 = $range2From <= $range1From && $range2To >= $range1From;
    $range1ToOverlapRange2 = $range2From <= $range1To && $range2To >= $range1To;

    if ($range2FromOverlapRange1 || $range2ToOverlapRange1 || $range1FromOverlapRange2 || $range1ToOverlapRange2) {
        $total++;
    }
}

echo "Total: $total\n";
