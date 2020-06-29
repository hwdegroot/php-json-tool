<?php

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use APp\Exceptions\ConversionFailedException;
use Artisan;
use Illuminate\Http\Request;

class FlattenController extends Controller
{
    /**
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    public function __invoke(Request $request, string $flatFilename)
    {
        $file = $request->file('file');

        $fromType = $this->getFromFiletype($file);
        $toType = $this->getOutputFiletype($flatFilename);

        $flattenedFile = $this->flatten($file, $fromType, $toType);
        if ($fromType == SupportedFileTypes::CSV) {
            throw new UnsupportedFiletypeException('csv is not supported as input type. It is already flattend.');
        }

        return response()->download(
            $flattenedFile,
            $flatFilename,
            [
                'Content-Type' => $toType,
            ]
        )
            ->deleteFileAfterSend();
    }

    private function flatten(string $filename, $fromType, $toType)
    {
        \Log::error($fromType);
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
                $response = $this->flatten(
                    $tmpFile['uri'],
                    $fromType == SupportedFileTypes::PHP ? SupportedFileTypes::JSON : SupportedFileTypes::PHP,
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
