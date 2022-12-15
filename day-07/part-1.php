<?php

class File
{
    public function __construct(
        public string $name = '',
        public int $size = 0,
    )
    {
    }
}

class DirectoryNode
{
    public ?DirectoryNode $parentDirectory = null;

    /**
     * @param string $name
     * @param array<int, DirectoryNode> $directories
     * @param array<int, File> $files
     */
    public function __construct(
        public string $name = '',
        public array $directories = [],
        public array $files = [],
    )
    {
    }

    public function addFile(File $file): void
    {
        $this->files[$file->name] = $file;
    }

    public function addDirectory(DirectoryNode $directory): void
    {
        $directory->parentDirectory = $this;

        $this->directories[$directory->name] = $directory;
    }
}

class DirectoryTraversal
{
    const THRESHOLD_SIZE = 100000;

    public int $sum = 0;

    public function sumDirWithSizeAtMost100_000(DirectoryNode $directory): int
    {
        $fileSize = array_reduce(
            $directory->files,
            fn(int $carry, File $file) => $carry + $file->size,
            0
        );

        $directoriesSize = array_reduce(
            $directory->directories,
            fn(int $carry, DirectoryNode $dir) => $carry + $this->sumDirWithSizeAtMost100_000($dir),
            0
        );

        $size = $fileSize + $directoriesSize;

        if ($size <= self::THRESHOLD_SIZE) {
            $this->sum += $size;
        }

        return $size;
    }
}

class Day7Part1
{
    const INPUT_FILE_NAME = 'input.txt';

    private array $directories;

    private ?DirectoryNode $currentDirectory = null;

    public function __construct()
    {
        $this->directories = [
            '/' => new DirectoryNode('/'),
        ];
    }

    public function solve(): void
    {
        $file = fopen(__DIR__ . '/' . self::INPUT_FILE_NAME, 'r');

        while(($line = fgets($file)) !== false) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $this->parseLine($line);
        }

        $traversal = new DirectoryTraversal();
        $traversal->sumDirWithSizeAtMost100_000($this->directories['/']);

        echo "Answer: $traversal->sum\n";
    }

    private function parseLine(string $line): void
    {
        if (str_starts_with($line, '$ cd')) {
            $this->parseChangeDirectory($line);
            return;
        }

        // Do nothing if it's a list mode
        if (str_starts_with($line, '$ ls')) {
            return;
        }

        $this->parseCommandOutput($line);
    }

    private function parseChangeDirectory(string $line): void
    {
        $targetDirectory = str_replace('$ cd ', '', $line);

        // Move current directory pointer to parent category
        if ($targetDirectory === '..') {
            $this->currentDirectory = $this->currentDirectory->parentDirectory;
            return;
        }

        if ($targetDirectory === '/') {
            $this->currentDirectory = $this->directories[$targetDirectory];
            return;
        }

        // Move current directory pointer to the target directory
        $this->currentDirectory = $this->currentDirectory->directories[$targetDirectory];
    }

    private function parseCommandOutput(string $line): void
    {
        if (str_starts_with($line, 'dir')) {
            $this->addDirectory($line);
            return;
        }

        $this->addFile($line);
    }

    private function addDirectory(string $line): void
    {
        $directoryName = str_replace('dir ', '', $line);

        // Do nothing if the directory is already created
        if (array_key_exists($directoryName, $this->currentDirectory->directories)) {
            return;
        }

        // Add the directory to the list of the current directory
        $this->currentDirectory->addDirectory(new DirectoryNode($directoryName));
    }

    private function addFile(string $line): void
    {
        [$size, $filename] = explode(' ', $line);

        if (array_key_exists($filename, $this->currentDirectory->files)) {
            return;
        }

        $this->currentDirectory->addFile(new File($filename, $size));
    }
}

$problem = new Day7Part1();
$problem->solve();
