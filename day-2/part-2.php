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
];

$winCriteria = [
    1 => 3,
    2 => 1,
    3 => 2,
];

$loseCriteria = array_flip($winCriteria);

$totalScore = 0;

while (($line = fgets($file)) !== false) {
    [$opponentPlays, $myAction] = explode(' ', trim($line));

    $opponentPlays = $normalize[$opponentPlays];

    $score = 0;

    // I need to end in a draw
    if ($myAction === 'X') {
        $score += $winCriteria[$opponentPlays];
    } else if ($myAction === 'Y') {
        $score += $opponentPlays + DRAW_SCORE;
    } else {
        $score += $loseCriteria[$opponentPlays] + WIN_SCORE;
    }

    $totalScore += $score;
}

echo $totalScore . "\n";
