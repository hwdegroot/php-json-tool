<?php

namespace App\Console\Commands\Serialize;

class PhpToJson extends SerializeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serialize:php2json
        {filename : The file that needs to be serialized}
        {--raw : Output the data as raw as possible}
        {--out-file= : Store the output in a file}
        {--to-csv : Create a tab seperated output. This will do --flatten as well}
        {--flatten : Flatten the keys into dot seperated keys instead of nested}
        {--unflatten : Flatten the keys into dot seperated keys instead of nested. Do not use with --flatten}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serialize a php file to json.';

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
        \Log::error($this->option());
        \Log::error($this->argument());
        $this->verifyOptionFlags();

        $rawData = (bool) $this->option('raw');
        $filename = $this->argument('filename');
        $contents = require $filename;

        if ((bool) $this->option('flatten') || (bool) $this->option('to-csv')) {
            $contents = $this->flatten($contents);
        }

        if ((bool) $this->option('unflatten')) {
            $contents = $this->unflatten($contents);
        }

        $encoded = (bool) $this->option('to-csv')
            ? $this->toCsvOutput($contents, (bool) $this->option('out-file') ? PHP_EOL : '')
            : $encoded = json_encode($contents, JSON_PRETTY_PRINT);

        if (!empty($this->option('out-file'))) {
            $outFilePath = $this->option('out-file');
            $this->writeFile($outFilePath, $encoded);
        } else {
            $this->writeToConsole($encoded, $rawData);
        }
    }
}
