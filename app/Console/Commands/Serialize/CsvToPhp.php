<?php

declare(strict_types=1);

namespace App\Console\Commands\Serialize;

use Illuminate\Support\Str;
use Laminas\Code\Generator\ValueGenerator;

class CsvToPhp extends SerializeCommand
{
    const TAB = "\t";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serialize:csv2php
        {filename : The file that needs to be serialized}
        {--raw : Output the data as raw as possible}
        {--delimiter=<TAB> : Delimiter used to seperate the columns in CSV. Supports <TAB> for tab delimited}
        {--out-file= : Store the output in a file}
        {--to-json : Output the data as JSON blob}
        {--unflatten : Flatten the keys into dot seperated keys instead of nested. Do not use with --flatten}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serialize a csv file to php.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rawData = (bool) $this->option('raw');
        $filename = $this->argument('filename');
        $file = $this->readFile($filename);

        $contents = $this->parse($file, $this->option('delimiter'));

        if ((bool) $this->option('unflatten')) {
            $contents = $this->unflatten($contents);
        }

        $generatedContents = (bool) $this->option('to-json')
            ? json_encode($contents, \JSON_PRETTY_PRINT)
            : $this->generate($contents)->generate();

        if (!empty($this->option('out-file'))) {
            $outFilePath = $this->option('out-file');

            if ((bool) $this->option('to-json')) {
                return parent::writeFile($outFilePath, $generatedContents);
            }

            return $this->writeFile($outFilePath, $generatedContents);
        }

        $this->writeToConsole($generatedContents, $rawData);
    }

    protected function parse($contents, string $delimiter = "\t"): array
    {
        if ($this->isTabDelimited($delimiter)) {
            $delimiter = self::TAB;
        }

        $result = [];

        $lines = array_filter(
            explode(\PHP_EOL, $contents),
            function ($line) use ($delimiter) {
                return Str::of($line)
                    ->trim($delimiter)
                    ->isNotEmpty();
            }
        );
        foreach ($lines as $line) {
            if (!Str::contains($line, $delimiter)) {
                $this->warn("'{$line}' does not contain delimiter to split");

                continue;
            }

            if (Str::of($line)->trim($delimiter)->isEmpty()) {
                $this->warn('Empty line detected. Skipping');
            }

            list($key, $value) = explode($delimiter, $line);
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Write translations to a file.
     *
     * @param mixed $contents
     */
    protected function writeFile(string $filename, $contents): void
    {
        file_put_contents($filename, '<?php'.\PHP_EOL.\PHP_EOL.'return ');
        file_put_contents($filename, $contents, \FILE_APPEND);
        file_put_contents($filename, ';', \FILE_APPEND);
        file_put_contents($filename, \PHP_EOL, \FILE_APPEND);
    }

    /**
     * Use the spiffy generator to generate proper arrays with square brackets
     * instead of these PHP 5.0 `array`s.
     */
    private function generate(array $contents): ValueGenerator
    {
        return new ValueGenerator($contents, ValueGenerator::TYPE_ARRAY_SHORT);
    }

    private function isTabDelimited(?string $delimiter): bool
    {
        if ($delimiter == '\t') {
            return true;
        }

        if ($delimiter == '<TAB>') {
            return true;
        }

        if ($delimiter == "\t") {
            return true;
        }

        return false;
    }
}
