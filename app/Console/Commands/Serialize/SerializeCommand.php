<?php

declare(strict_types=1);

namespace App\Console\Commands\Serialize;

use App\Console\Exceptions\MissingArgumentException;
use App\Console\Exceptions\NotSupportedException;
use App\Console\Exceptions\UnsupportedArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

function hasStringKeys(array $array): bool
{
    return \count(array_filter(array_keys($array), 'is_string')) > 0;
}

abstract class SerializeCommand extends Command
{
    protected function verifyOptionFlags(): bool
    {
        if ((bool) $this->option('flatten') || (bool) $this->option('to-csv')) {
            if ((bool) $this->option('unflatten')) {
                throw new UnsupportedArgumentException('--unflatten can not be used with --flatten or --to-csv option');
            }
        }

        return true;
    }

    /**
     * Read the file from disk.
     */
    protected function readFile(string $filename): string
    {
        if (!File::isFile($filename)) {
            throw new FileNotFoundException('Unable to locate file');
        }

        return File::get($filename);
    }

    protected function writeToConsole($contents, bool $rawData = false): void
    {
        if (!$rawData) {
            $this->line('---8<-----------------------------------------------------------------');
        }
        $this->line($contents);
        if (!$rawData) {
            $this->line('---8<-----------------------------------------------------------------');
        }
    }

    /**
     * Method to flatten keys into dot seperated entries.
     *
     * @param mixed $content
     * @param mixed $prefix
     * @param mixed $r
     * @param mixed $result
     *
     * @returns mixed content
     */
    protected function flatten(array $content, $prefix = '', $result = []): array
    {
        foreach ($content as $key => $value) {
            $localKey = !empty($prefix) ? "{$prefix}.{$key}" : $key;
            if (!\is_array($content[$key]) || !hasStringKeys($content[$key])) {
                $result[$localKey] = $value;

                continue;
            }

            $result = $this->flatten($content[$key], $localKey, $result);
        }

        return $result;
    }

    /**
     * Nest dotseperated keys into associative array.
     */
    protected function unflatten(array $content): array
    {
        $result = [];
        foreach ($content as $key => $value) {
            $path = explode('.', $key);
            $ref = &$result;
            foreach ($path as $p) {
                if (!\array_key_exists($p, $ref)) {
                    $ref[$p] = [];
                }
                $ref = &$ref[$p];
            }

            if (\is_array($value) && hasStringKeys($value)) {
                $ref = $this->unflatten($value);
            } else {
                $ref = $value;
            }
        }

        return $result;
    }

    /**
     * Create tab seperated entries from file.
     *
     * @param mixed $flattendContents
     * @param mixed $eol
     */
    protected function toCsvOutput($flattendContents, $eol = ''): array
    {
        if (!(bool) $this->option('out-file')) {
            throw new MissingArgumentException('Cannot convert to CSV if no outfile is defined');
        }

        $result = [];

        foreach ($flattendContents as $key => $value) {
            if (\is_array($value)) {
                if (hasStringKeys($value)) {
                    throw new NotSupportedException('Converting to CSV output only works for flattened content');
                }

                foreach ($value as $index => $val) {
                    $result[] = "{$key}.{$index}\t{$val}".$eol;
                }
            } else {
                $result[] = "{$key}\t{$value}".$eol;
            }
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
        file_put_contents($filename, $contents);
        file_put_contents($filename, \PHP_EOL, \FILE_APPEND);
    }
}
