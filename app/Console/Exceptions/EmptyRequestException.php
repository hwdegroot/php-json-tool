<?php

declare(strict_types=1);

namespace App\Console\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class EmptyRequestException extends HttpException
{
    public function __construct($message, ?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_PRECONDITION_FAILED, $message, $previous);
    }
}
