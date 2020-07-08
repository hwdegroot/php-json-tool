<?php

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use APp\Exceptions\ConversionFailedException;
use App\Exceptions\UnsupportedConversionException;
use Artisan;
use Illuminate\Http\Request;

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

        $convertedFile = $this->convert($file, $fromType, $toType);

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
        } elseif ($fromType == SupportedFileTypes::CSV) {
            $command = 'serialize:csv2php';

            if ($toType == SupportedFileTypes::JSON) {
                $arguments['--to-json'] = true;
            }
        } else {
            $command = 'serialize:json2php';
        }

        $exitCode = Artisan::call($command, $arguments);

        if ($exitCode === 0) {
            return $tmpFile['uri'];
        }

        throw new ConversionFailedException('Something went while processing the file. Please try again');
    }
}
