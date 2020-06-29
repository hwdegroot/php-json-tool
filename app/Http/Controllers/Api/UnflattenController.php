<?php

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use APp\Exceptions\ConversionFailedException;
use App\Exceptions\UnsupportedFiletypeException;
use Artisan;
use Illuminate\Http\Request;

class UnflattenController extends Controller
{
    /**
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    public function __invoke(Request $request, string $unflatFilename)
    {
        $file = $request->file('file');

        $fromType = $this->getFromFiletype($file);
        $toType = $this->getOutputFiletype($unflatFilename);

        if ($fromType == SupportedFileTypes::CSV) {
            throw new UnsupportedFiletypeException('Nesting from CSV is currently not supported');
        }

        $unflattenedFile = $this->unflatten($file, $fromType, $toType);

        return response()->download(
            $unflattenedFile,
            $unflatFilename,
            [
                'Content-Type' => $toType,
            ]
        )
            ->deleteFileAfterSend();
    }

    private function unflatten(string $filename, SupportedFileTypes $fromType, SupportedFileTypes $toType)
    {
        $tmpFile = stream_get_meta_data(tmpfile());
        $arguments = [
            'filename' => $filename,
            '--out-file' => $tmpFile['uri'],
        ];

        if ($fromType == SupportedFileTypes::PHP) {
            $command = 'serialize:php2json';
        } else {
            $command = 'serialize:json2php';
        }

        $arguments['--unflatten'] = true;

        $exitCode = Artisan::call($command, $arguments);

        if ($exitCode === 0) {
            if ($fromType == $toType) {
                return $this->unflatten(
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
