<?php

use App\Enum\SupportedFileTypes;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use function Tests\assertSnapshotEquals;

it('should flatten a file from PHP to PHP', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.php'),
                'nested.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.php', $response);
});

it('should flatten a file from JSON to JSON', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.json'),
                'nested.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.json', $response);
});

it('should flatten a file from JSON to PHP', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.json'),
                'nested.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.php', $response);
});

it('should flatten a PHP file to JSON', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.php'),
                'nested.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.json', $response);
});

it('should flatten a file from PHP to CSV', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.csv',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.php'),
                'nested.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.csv', $response);
});

it('should flatten a file from JSON to CSV', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.csv',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/nested.json'),
                'nested.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('flat.csv', $response);
});

it('should not allow flatten from a csv file', function () {
    $response = $this->post(
        '/api/v1/flatten/'.Str::uuid().'.json',
        [
            'file' => UploadedFile::fake()
                ->create('not-allowed.csv', 0, SupportedFileTypes::CSV),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});
