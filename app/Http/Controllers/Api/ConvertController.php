<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use App\Exceptions\ConversionFailedException;
use App\Exceptions\UnsupportedConversionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConvertController extends Controller
{
    /**
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    public function __invoke(Request $request, string $convertedFilename)
    {
        $file = $request->file('file');

        $fromType = $this->getFromFiletype($file);
        $toType = $this->getOutputFiletype($convertedFilename);

        if ($toType == $fromType) {
            throw new UnsupportedConversionException("Can only convert between same filetypes {$fromType}");
        }

        $convertedFile = $this->convert($file->path(), $fromType, $toType);

        return response()->download(
            $convertedFile,
            $convertedFilename,
            [
                'Content-Type' => $toType,
            ]
        )
            ->deleteFileAfterSend();
    }

    private function convert(string $filename, SupportedFileTypes $fromType, SupportedFileTypes $toType)
    {
        $tmpFile = stream_get_meta_data(tmpfile());
        $arguments = [
            'filename' => $filename,
            '--out-file' => $tmpFile['uri'],
        ];

        if ($fromType == SupportedFileTypes::PHP) {
            $command = 'serialize:php2json';
            // Check if '--to-csv is required'
            if ($toType == SupportedFileTypes::CSV) {
                $arguments['--to-csv'] = true;
            }
        } elseif ($fromType == SupportedFileTypes::CSV) {
            $command = 'serialize:csv2php';

            if ($toType == SupportedFileTypes::JSON) {
                $arguments['--to-json'] = true;
            }
        } else {
            $command = 'serialize:json2php';
            // Check if '--to-csv is required'
            if ($toType == SupportedFileTypes::CSV) {
                $arguments['--to-csv'] = true;
            }
        }

        $exitCode = Artisan::call($command, $arguments);

        if ($exitCode === 0) {
            return $tmpFile['uri'];
        }

        throw new ConversionFailedException('Something went while processing the file. Please try again');
    }
}
