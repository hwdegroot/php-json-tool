<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use App\Exceptions\ConversionFailedException;
use App\Exceptions\UnsupportedFiletypeException;
use Illuminate\Support\Facades\Artisan;

class UnflattenController extends Controller
{
    /**
     * {@inheritdoc}
     */
    protected function validateFileTypes(SupportedFileTypes $fromType, SupportedFileTypes $toType): void
    {
        if ($toType == SupportedFileTypes::CSV) {
            throw new UnsupportedFiletypeException('Can not unflatten to CSV');
        }
    }

    protected function process(string $filename, SupportedFileTypes $fromType, SupportedFileTypes $toType)
    {
        $tmpFile = stream_get_meta_data(tmpfile());
        $arguments = [
            'filename' => $filename,
            '--out-file' => $tmpFile['uri'],
        ];

        if ($fromType == SupportedFileTypes::PHP) {
            $command = 'serialize:php2json';
        } elseif ($fromType == SupportedFileTypes::CSV) {
            $command = 'serialize:csv2php';

            if ($toType == SupportedFileTypes::JSON) {
                $arguments['--to-json'] = true;
            }
        } else {
            $command = 'serialize:json2php';
        }

        $arguments['--unflatten'] = true;

        $exitCode = Artisan::call($command, $arguments);

        if ($exitCode === 0) {
            if ($fromType == $toType) {
                return $this->process(
                    $tmpFile['uri'],
                    $fromType == SupportedFileTypes::PHP
                        ? SupportedFileTypes::create(SupportedFileTypes::JSON)
                        : SupportedFileTypes::create(SupportedFileTypes::PHP),
                    $toType
                );
            }

            return $tmpFile['uri'];
        }

        throw new ConversionFailedException('Something went while processing the file. Please try again');
    }
}
