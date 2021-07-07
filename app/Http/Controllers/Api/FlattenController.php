<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use App\Exceptions\ConversionFailedException;
use App\Exceptions\UnsupportedFiletypeException;
use Illuminate\Support\Facades\Artisan;

class FlattenController extends Controller
{
    /**
     * {@inheritdoc}
     */
    protected function validateFileTypes(SupportedFileTypes $fromType, SupportedFileTypes $toType): void
    {
        if ($fromType == SupportedFileTypes::CSV) {
            throw new UnsupportedFiletypeException('csv is not supported as input type. It is already flattend.');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionFailedException
     */
    protected function process(string $filename, SupportedFileTypes $fromType, SupportedFileTypes $toType)
    {
        $tmpFile = stream_get_meta_data(tmpfile());
        $arguments = [
            'filename' => $filename,
            '--out-file' => $tmpFile['uri'],
            '-vvv' => true,
        ];

        if ($fromType == SupportedFileTypes::PHP) {
            $command = 'serialize:php2json';
        } else {
            $command = 'serialize:json2php';
        }

        if ($toType == SupportedFileTypes::CSV) {
            $arguments['--to-csv'] = true;
        } else {
            $arguments['--flatten'] = true;
        }

        $exitCode = Artisan::call($command, $arguments);

        if ($exitCode === 0) {
            if ($fromType == $toType) {
                $response = $this->process(
                    $tmpFile['uri'],
                    $fromType == SupportedFileTypes::PHP ?
                        SupportedFileTypes::create(SupportedFileTypes::JSON) :
                        SupportedFileTypes::create(SupportedFileTypes::PHP),
                    $toType
                );
                // Remove the intermediate file.
                unlink($tmpFile['uri']);

                return $response;
            }

            return $tmpFile['uri'];
        }

        throw new ConversionFailedException('Something went while processing the file. Please try again');
    }
}
