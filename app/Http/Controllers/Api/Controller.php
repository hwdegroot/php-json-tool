<?php

namespace App\Http\Controllers\Api;

use App\Enum\SupportedFileTypes;
use App\Exceptions\InvalidFiletypeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class Controller
{
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
