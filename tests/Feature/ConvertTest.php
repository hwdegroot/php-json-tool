<?php

use App\Enum\SupportedFileTypes;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use function Tests\assertSnapshotEquals;

it('should convert a file from PHP to JSON', function () {
    $response = $this->post(
        '/api/v1/convert/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.json'),
                'nested.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.php', $response);
});

it('should convert a file from JSON to PHP', function () {
    $response = $this->post(
        '/api/v1/convert/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.php'),
                'nested.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.json', $response);
});

it('should not support conversion from CSV', function () {
    $response = $this->post(
        '/api/v1/convert/'.Str::uuid().'.csv',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.php'),
                'nested.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});

it('should not support conversion to CSV', function () {
    $response = $this->post(
        '/api/v1/convert/'.Str::uuid().'.json',
        [
            'file' => UploadedFile::fake()
                ->create('unsupported.xml', 0, 'application/xml'),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});
