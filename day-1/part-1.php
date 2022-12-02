<?php

$content = file_get_contents(__DIR__ . '/input.txt');

$chunks = explode("\n\n", $content);

$max = 0;

foreach ($chunks as $chunk) {
    $sum = array_sum(explode("\n", $chunk));

    if ($sum > $max) {
        $max = $sum;
    }
}

echo $max . "\n";
