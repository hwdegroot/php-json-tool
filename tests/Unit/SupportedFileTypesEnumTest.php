<?php

declare(strict_types=1);

use App\Enum\SupportedFileTypes;
use App\Exceptions\UnsupportedFiletypeException;

it('supports PHP filetype', function (): void {
    $this->assertEquals('text/x-php', SupportedFileTypes::fromFileExtension('php'));
});

it('supports CSV filetype', function (): void {
    $this->assertEquals('text/csv', SupportedFileTypes::fromFileExtension('csv'));
});

it('supports JSON filetype', function (): void {
    $this->assertEquals('application/json', SupportedFileTypes::fromFileExtension('json'));
});

it('throws an unsupported FileTypeException for non php|json|csv', function (): void {
    $this->expectException(UnsupportedFiletypeException::class);
    SupportedFileTypes::create('text/html');
});

it('gets PHP file extension', function (): void {
    $this->assertEquals('php', SupportedFileTypes::create('text/x-php')->getFileExtension());
});

it('gets CSV file extension', function (): void {
    $this->assertEquals('csv', SupportedFileTypes::create('text/csv')->getFileExtension());
});

it('gets CSV file extension from plain text', function (): void {
    $this->assertEquals('csv', SupportedFileTypes::create('text/plain')->getFileExtension());
});

it('gets JSON file extension', function (): void {
    $this->assertEquals('json', SupportedFileTypes::create('application/json')->getFileExtension());
});
