<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnsupportedFiletypeException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $message);
    }
}
