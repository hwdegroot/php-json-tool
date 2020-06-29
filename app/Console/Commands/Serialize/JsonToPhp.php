<?php

namespace App\Console\Commands\Serialize;

use Laminas\Code\Generator\ValueGenerator;

class JsonToPhp extends SerializeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serialize:json2php
        {filename : The file that needs to be serialized}
        {--raw : Output the data as raw as possible}
        {--out-file= : Store the output in a file}
        {--to-csv : Create a tab seperated output. This will do --flatten as well}
        {--flatten : Flatten the keys into dot seperated keys instead of nested do not use with --unflatten}
        {--unflatten : Flatten the keys into dot seperated keys instead of nested. Do not use with --flatten}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serialize a json file to php.';

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
        $this->verifyOptionFlags();

        $rawData = (bool) $this->option('raw');
        $filename = $this->argument('filename');
        $contents = $this->readFile($filename);
        $decoded = json_decode($contents, true);

        if ((bool) $this->option('flatten') || (bool) $this->option('to-csv')) {
            $decoded = $this->flatten($decoded);
        }

        if ((bool) $this->option('unflatten')) {
            $decoded = $this->unflatten($decoded);
        }

        $generatedContents = (bool) $this->option('to-csv')
            ? $this->toCsvOutput($decoded, PHP_EOL)
            : $generatedContents = $this->generate($decoded);

        if (!empty($this->option('out-file'))) {
            $outFilePath = $this->option('out-file');

            if ((bool) $this->option('to-csv')) {
                parent::writeFile($outFilePath, $generatedContents);
            } else {
                $this->writeFile($outFilePath, $generatedContents);
            }

            return;
        }

        $this->writeToConsole($generatedContents, $rawData);
    }

    /**
     * Write translations to a file.
     *
     * @param mixed $contents
     */
    protected function writeFile(string $filename, $contents): void
    {
        file_put_contents($filename, '<?php'.PHP_EOL.PHP_EOL.'return ');
        file_put_contents($filename, $contents, FILE_APPEND);
        file_put_contents($filename, ';', FILE_APPEND);
        file_put_contents($filename, PHP_EOL, FILE_APPEND);
    }

    /**
     * Use the spiffy generator to generate proper arrays with square brackets
     * instead of these PHP 5.0 `array`s.
     */
    private function generate(array $contents): ValueGenerator
    {
        return new ValueGenerator($contents, ValueGenerator::TYPE_ARRAY_SHORT);
    }
}
