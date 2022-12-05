<?php

class Day5Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    const STACK_OPERATION_REGEX = '/move (?<move_amount>[\d]*) from (?<from_stack>[\d]*) to (?<to_stack>[\d]*)/';

    private array $stacks = [];

    public function solve(): void
    {
        $input = file_get_contents(__DIR__ . '/' . self::INPUT_FILE_NAME);

        $segments = explode("\n\n", $input);

        $stackSetupLines = explode("\n", $segments[0]);
        $stackOperationLines = explode("\n", $segments[1]);

        $this->populateStacks($stackSetupLines);
        $this->performStackOperations($stackOperationLines);
        $this->printOutput();
    }

    private function populateStacks(array $stackSetupLines): void
    {
        unset($stackSetupLines[count($stackSetupLines) - 1]);

        foreach ($stackSetupLines as $line) {
            $stacks = str_split($line, 4);

            foreach ($stacks as $index => $stack) {
                $stackNumber = $index + 1;
                $stack = trim($stack);

                if (empty($stack)) {
                    continue;
                }

                if (!array_key_exists($stackNumber, $this->stacks)) {
                    $this->stacks[$stackNumber] = [];
                }

                $this->stacks[$stackNumber][] = $stack[1];
            }
        }

        // Revert the creates inside the stack+
        $this->stacks = array_map(function (array $crates) {
            return array_reverse($crates);
        }, $this->stacks);
    }

    private function performStackOperations(array $stackOperationLines): void
    {
        foreach ($stackOperationLines as $line) {
            if (empty($line)) {
                continue;
            }
            
            preg_match(self::STACK_OPERATION_REGEX, $line, $matches, PREG_OFFSET_CAPTURE);

            $moveAmount = $matches['move_amount'][0];
            $fromStack = $matches['from_stack'][0];
            $toStack = $matches['to_stack'][0];

            for ($i = 1; $i <= $moveAmount; $i++) {
                $this->stacks[$toStack][] = array_pop($this->stacks[$fromStack]);
            }
        }
    }

    private function printOutput(): void
    {
        $keys = array_keys($this->stacks);
        
        sort($keys);
        
        echo "Message: ";
        
        foreach ($keys as $key) {
            echo $this->stacks[$key][count($this->stacks[$key]) - 1];
        }
        echo "\n";
    }
}

$problem = new Day5Part1();
$problem->solve();
