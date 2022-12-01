<?php

declare(strict_types=1);

use App\Enum\SupportedFileTypes;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use function Tests\assertSnapshotEquals;

it('should flatten a file from PHP to PHP', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.php',
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

it('should flatten a file from JSON to JSON', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.json',
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

it('should flatten a file from JSON to PHP', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.php',
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

it('should flatten a PHP file to JSON', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.json',
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

it('should flatten a file from PHP to CSV', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.csv',
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

it('should flatten a file from JSON to CSV', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.csv',
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

it('should not allow flatten from a csv file', function (): void {
    $response = $this->post(
        '/api/flatten/'.Str::uuid().'.json',
        [
            'file' => UploadedFile::fake()
                ->create('not-allowed.csv', 0, SupportedFileTypes::CSV),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});
