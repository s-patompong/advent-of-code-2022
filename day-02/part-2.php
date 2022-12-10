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

    if ($myAction === 'X') {
        // X means I need to lose, so I need to play the 'lose' side of the winCriteria array
        $score += $winCriteria[$opponentPlays];
    } else if ($myAction === 'Y') {
        // Y means I need to draw, I need to play whatever the opponent is playing, I also get
        // the extra score from the DRAW_SCORE
        $score += $opponentPlays + DRAW_SCORE;
    } else {
        // Z means I need to win, I need to play the 'win' side of the winCriteria, which is basically
        // the array value with the opponent turn as key once we flipped the winCriteria array
        $score += $loseCriteria[$opponentPlays] + WIN_SCORE;
    }

    $totalScore += $score;
}

echo $totalScore . "\n";
