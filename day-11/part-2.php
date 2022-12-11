<?php

use Brick\Math\BigInteger;

include_once 'vendor/autoload.php';

// Note: The usage of BigInteger in this file is useless, it was before I found the actual solution

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
     * @var array<int, BigInteger>
     */
    public array $items = [];

    public string $operator = '+';

    public OperateeType $operateeType = OperateeType::OldValue;

    public BigInteger $operateeValue;

    public BigInteger $divisibleByValue;

    public int $monkeyIndexToThrowToWhenTrue = 0;

    public int $moneyIndexToThrowToWhenFalse = 0;

    public int $throwCount = 0;

    public function __construct()
    {
    }

    /**
     * @param array<int, Monkey> $monkeys
     * @param BigInteger $divisor
     * @return void
     */
    public function processThrows(array $monkeys, BigInteger $divisor): void
    {
        foreach ($this->items as $item) {
            $worryLevel = $this->getWorryLevel($item)->mod($divisor);

            $throwToMonkeyIndex = $worryLevel->mod($this->divisibleByValue)->isZero()
                ? $this->monkeyIndexToThrowToWhenTrue
                : $this->moneyIndexToThrowToWhenFalse;

            $monkeys[$throwToMonkeyIndex]->items[] = $worryLevel;

            $this->throwCount++;
        }

        // Clear items from this monkey
        $this->items = [];
    }

    private function getWorryLevel(BigInteger $item): BigInteger
    {
        $value = $this->operateeType === OperateeType::OldValue
            ? $item
            : $this->operateeValue;

        if ($this->operator === self::OPERATION_PLUS) {
            return $item->plus($value);
        }

        return $item->multipliedBy($value);
    }
}

class Day11Part2
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
                $monkey->items = array_map(function(string $item) {
                    return BigInteger::of($item);
                }, explode(', ', $line));
                continue;
            }

            if (str_starts_with($line, 'Operation:')) {
                $line = str_replace('Operation: new = old ', '', $line);
                [$operator, $operatee] = explode(' ', $line);
                $monkey->operator = $operator;
                if ($operatee !== 'old') {
                    $monkey->operateeType = OperateeType::NewValue;
                    $monkey->operateeValue = BigInteger::of($operatee);
                }
                continue;
            }

            if (str_starts_with($line, 'Test:')) {
                $line = str_replace('Test: divisible by ', '', $line);
                $monkey->divisibleByValue = BigInteger::of($line);
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
        $divisor = array_reduce($this->monkeys, function(BigInteger $currentValue, Monkey $monkey) {
            return $currentValue->multipliedBy($monkey->divisibleByValue);
        }, BigInteger::of(1));

        // Loop for 10,000 rounds
        for ($i = 1; $i <= 10_000; $i++) {
            foreach ($this->monkeys as $monkey) {
                $monkey->processThrows($this->monkeys, $divisor);
            }

            echo "== After round $i ==\n";
            foreach ($this->monkeys as $index => $monkey) {
                echo "Monkey $index inspected items $monkey->throwCount times.\n";
            }

            echo "\n";
        }

        usort($this->monkeys, function(Monkey $a, Monkey $b) {
            return $b->throwCount - $a->throwCount;
        });

        $answer = $this->monkeys[0]->throwCount * $this->monkeys[1]->throwCount;

        echo "Answer: $answer\n";
    }
}

$problem = new Day11Part2();
$problem->solve();
