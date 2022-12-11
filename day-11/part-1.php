<?php

enum OperateeType
{
    case OldValue;

    case NewValue;
}

class Monkey
{
    const OPERATION_PLUS = '+';

    const OPERATION_MULTIPLY = '*';

    /**
     * @var array<int, int>
     */
    public array $items = [];

    public string $operator = '+';

    public OperateeType $operateeType = OperateeType::OldValue;

    public ?int $operateeValue = 0;

    public int $divisibleByValue = 0;

    public int $monkeyIndexToThrowToWhenTrue = 0;

    public int $moneyIndexToThrowToWhenFalse = 0;

    public int $throwCount = 0;

    public function __construct()
    {
    }

    /**
     * @param array<int, Monkey> $monkeys
     * @return void
     */
    public function processThrows(array $monkeys): void
    {
        foreach ($this->items as $item) {
            $worryLevel = (int) floor($this->getWorryLevel($item) / 3);

            $throwToMonkeyIndex = $worryLevel % $this->divisibleByValue === 0
                ? $this->monkeyIndexToThrowToWhenTrue
                : $this->moneyIndexToThrowToWhenFalse;

            // Throw to another monkey
            $monkeys[$throwToMonkeyIndex]->items[] = $worryLevel;

            $this->throwCount++;
        }

        $this->items = [];
    }

    private function getWorryLevel(int $item): int
    {
        $value = $this->operateeType === OperateeType::OldValue
            ? $item
            : $this->operateeValue;

        if ($this->operator === self::OPERATION_PLUS) {
            return $item + $value;
        }

        return $item * $value;
    }
}

class Day11Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    /**
     * @var array<int, Monkey>
     */
    private array $monkeys = [];

    public function solve(): void
    {
        $this->parseInput();

        $this->processThrows();
    }

    private function parseInput(): void
    {
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        $monkey = new Monkey();

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            // Do nothing for the Monkey line
            if (str_starts_with($line, 'Monkey')) {
                $monkey = new Monkey();
                continue;
            }

            if (str_starts_with($line, 'Starting items:')) {
                $line = str_replace('Starting items: ', '', $line);
                $monkey->items = array_map(function (string $item) {
                    return (int) $item;
                }, explode(', ', $line));
                continue;
            }

            if (str_starts_with($line, 'Operation:')) {
                $line = str_replace('Operation: new = old ', '', $line);
                [$operator, $operatee] = explode(' ', $line);
                $monkey->operator = $operator;
                if ($operatee !== 'old') {
                    $monkey->operateeType = OperateeType::NewValue;
                    $monkey->operateeValue = (int) $operatee;
                }
                continue;
            }

            if (str_starts_with($line, 'Test:')) {
                $line = str_replace('Test: divisible by ', '', $line);
                $monkey->divisibleByValue = (int) $line;
                continue;
            }

            if (str_starts_with($line, 'If true:')) {
                $line = str_replace('If true: throw to monkey ', '', $line);
                $monkey->monkeyIndexToThrowToWhenTrue = (int) $line;
                continue;
            }

            if (str_starts_with($line, 'If false:')) {
                $line = str_replace('If false: throw to monkey ', '', $line);
                $monkey->moneyIndexToThrowToWhenFalse = (int) $line;
                continue;
            }

            // Empty line, add the current monkey object to the array
            $this->monkeys[] = $monkey;
        }

        $this->monkeys[] = $monkey;
    }

    private function processThrows(): void
    {
        // Loop for 20 rounds
        for ($i = 1; $i <= 20; $i++) {
            foreach ($this->monkeys as $monkey) {
                $monkey->processThrows($this->monkeys);
            }
        }

        usort($this->monkeys, function(Monkey $a, Monkey $b) {
            return $b->throwCount - $a->throwCount;
        });

        $answer = $this->monkeys[0]->throwCount * $this->monkeys[1]->throwCount;

        echo "Answer: $answer\n";
    }
}

$problem = new Day11Part1();
$problem->solve();
