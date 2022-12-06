<?php

$input = trim(file_get_contents(__DIR__ . '/input.txt'));

$length = strlen($input);

for ($i = 13; $i < $length; $i++) {
    $str = substr($input, $i - 13, 14);
    
    $charCounts = count_chars($str, 1);
    
    $duplicates = array_filter($charCounts, fn($count) => $count > 1);
    
    if (count($duplicates) === 0) {
        echo sprintf("Answer: %d\n", $i + 1);
        break;
    }
}


