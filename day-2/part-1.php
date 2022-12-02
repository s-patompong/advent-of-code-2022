<?php

const DRAW_SCORE = 3;
const WIN_SCORE = 6;

$file = fopen(__DIR__ . '/input.txt', 'r');

// 1 -> rock, 2 -> paper, 3 -> scissor
// 1 wins 3
// 2 wins 1
// 3 wins 2

// For simplicity, we won't use the ascii way, (A-A+1) = 1 for example
$normalize = [
    'A' => 1,
    'B' => 2,
    'C' => 3,
    'X' => 1,
    'Y' => 2,
    'Z' => 3,
];

$winCriteria = [
    1 => 3,
    2 => 1,
    3 => 2,
];

$totalScore = 0;

while (($line = fgets($file)) !== false) {
    [$opponentPlays, $iPlay] = explode(' ', trim($line));

    $opponentPlays = $normalize[$opponentPlays];
    $iPlay = $normalize[$iPlay];

    $score = $iPlay;

    // DRAW -> I get 3 more score
    // WIN -> I get 6 more score
    // LOSE -> I don't get any score
    if ($opponentPlays === $iPlay) {
        $score += DRAW_SCORE;
    } else if ($winCriteria[$iPlay] === $opponentPlays) {
        $score += WIN_SCORE;
    }

    $totalScore += $score;
}

echo $totalScore . "\n";
