<?php

declare(strict_types=1);

use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

it('has no file passed fails with InvalidFiletypeException', function (): void {
    $response = $this->post('/api/convert/thisshouldfail.json');
    $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
});

it('has an unsupported file in the input fails with UnsupportedFileTypeException', function (): void {
    $response = $this->post(
        '/api/convert/output.json',
        [
            'file' => UploadedFile::fake()
                ->create('unsupported.xml', 0, 'application/xml'),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});

it('has an unsupported file in the output fails with UnsupportedFileTypeException', function (): void {
    $response = $this->post(
        '/api/convert/output.xml',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.json'),
                'nested.json',
                'application/json'
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});
