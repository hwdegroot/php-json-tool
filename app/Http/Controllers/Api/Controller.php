<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Console\Exceptions\EmptyRequestException;
use App\Enum\SupportedFileTypes;
use App\Exceptions\InvalidFiletypeException;
use App\Exceptions\UnsupportedFiletypeException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class Controller
{
    /**
     * Invocation of the controllet to process the file.
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    public function __invoke(Request $request, string $filename)
    {
        list($inputFilepath, $fromType, $toType) = $this->getDataFromRequest($request, $filename);

        $resultData = $this->process($inputFilepath, $fromType, $toType);
        $this->validateFileTypes($fromType, $toType);

        return response()->download(
            $resultData,
            $filename,
            [
                'Content-Type' => $toType,
            ]
        )
            ->deleteFileAfterSend();
    }

    /**
     * hook to validate the file types.
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    abstract protected function validateFileTypes(SupportedFileTypes $fromType, SupportedFileTypes $toType): void;

    /**
     * Abstract method that should be used to process the file.
     *
     * @throws\App\Exceptions\ConversionFailedException
     */
    abstract protected function process(string $filename, SupportedFileTypes $fromType, SupportedFileTypes $toType);

    /**
     * Get the data from the request.
     */
    protected function getDataFromRequest(Request $request, string $filename)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            return [
                $file->path(),
                $this->getFromFiletype($file),
                $this->getOutputFiletype($filename),
            ];
        }

        $fromFile = $request->header('input-format');
        $fileContents = $request->getContent();

        if ($fromFile === null || !SupportedFileTypes::isFiletypeSupported($fromFile)) {
            throw new UnsupportedFiletypeException('Please provide a valid conversion type');
        }

        if ($fileContents === null) {
            throw new EmptyRequestException('The request should contain a file to convert');
        }

        $inputFilepath = stream_get_meta_data(tmpfile());
        File::put($inputFilepath['uri'], $fileContents);

        return [
            $inputFilepath['uri'],
            SupportedFileTypes::fromShortName($request->header('Input-Format')),
            $this->getOutputFiletype($filename),
        ];
    }

    /**
     * Get the type of the uploaded file.
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException
     */
    protected function getOutputFiletype(string $outputFilename): SupportedFileTypes
    {
        return SupportedFileTypes::fromFileExtension(
            Str::afterLast($outputFilename, '.')
        );
    }

    /**
     * Get the type of the uploaded file.
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException
     * @throws \App\Exceptions\InvalidFiletypeException
     */
    protected function getFromFiletype(?UploadedFile $file): SupportedFileTypes
    {
        if ($file === null) {
            throw new InvalidFiletypeException('A file for conversion is expected. Please provide one');
        }

        return SupportedFileTypes::create(
            File::mimeType($file)
        );
    }
}
