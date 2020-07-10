<?php

use App\Enum\SupportedFileTypes;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use function Tests\assertSnapshotEquals;

it('should convert a file from PHP to JSON', function () {
    $response = $this->post(
        '/api/convert/'.Str::uuid().'.php',
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
        '/api/convert/'.Str::uuid().'.json',
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

it('should convert a file from CSV to PHP', function () {
    $response = $this->post(
        '/api/convert/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                SupportedFileTypes::CSV
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat-unmodified.php', $response);

    $response = $this->post(
        '/api/convert/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                'text/plain'
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat-unmodified.php', $response);
});

it('should convert a file from CSV to JSON', function () {
    $response = $this->post(
        '/api/convert/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                SupportedFileTypes::CSV
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat-unmodified.json', $response);

    $response = $this->post(
        '/api/convert/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                'text/plain'
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat-unmodified.json', $response);
});

it('should not support conversion between same filetypes', function () {
    $response = $this->post(
        '/api/convert/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.json'),
                'flat.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
});
