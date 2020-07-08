<?php

use App\Enum\SupportedFileTypes;
use App\Exceptions\UnsupportedFiletypeException;

it('supports PHP filetype', function () {
    $this->assertEquals('text/x-php', SupportedFileTypes::fromFileExtension('php'));
});

it('supports CSV filetype', function () {
    $this->assertEquals('text/csv', SupportedFileTypes::fromFileExtension('csv'));
});

it('supports JSON filetype', function () {
    $this->assertEquals('application/json', SupportedFileTypes::fromFileExtension('json'));
});

it('throws an unsupported FileTypeException for non php|json|csv', function () {
    $this->expectException(UnsupportedFiletypeException::class);
    SupportedFileTypes::create('text/html');
});

it('gets PHP file extension', function () {
    $this->assertEquals('php', SupportedFileTypes::create('text/x-php')->getFileExtension());
});

it('gets CSV file extension', function () {
    $this->assertEquals('csv', SupportedFileTypes::create('text/csv')->getFileExtension());
});

it('gets CSV file extension from plain text', function () {
    $this->assertEquals('csv', SupportedFileTypes::create('text/plain')->getFileExtension());
});

it('gets JSON file extension', function () {
    $this->assertEquals('json', SupportedFileTypes::create('application/json')->getFileExtension());
});
