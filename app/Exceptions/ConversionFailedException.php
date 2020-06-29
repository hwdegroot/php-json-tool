<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConversionFailedException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, $message);
    }
}
