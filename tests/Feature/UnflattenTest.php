<?php

declare(strict_types=1);

use App\Enum\SupportedFileTypes;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use function Tests\assertSnapshotEquals;

it('should nest a file from PHP to PHP', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.php'),
                'flat.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.php', $response);
});

it('should nest a file from JSON to JSON', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.json'),
                'flat.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.json', $response);
});

it('should nest a file from JSON to PHP', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.json'),
                'flat.json',
                SupportedFileTypes::JSON
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.php', $response);
});

it('should nest a file from PHP to JSON', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.php'),
                'flat.php',
                SupportedFileTypes::PHP
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.json', $response);
});

it('should nest a file from CSV to PHP', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.php',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                SupportedFileTypes::CSV
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.php', $response);
});

it('should nest a file from CSV to JSON', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.json',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                SupportedFileTypes::CSV
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    assertSnapshotEquals('nested.json', $response);
});

it('should not allow nesting a file to CSV', function (): void {
    $response = $this->post(
        '/api/unflatten/'.Str::uuid().'.csv',
        [
            'file' => new UploadedFile(
                base_path('tests/__snapshots__/flat.csv'),
                'flat.csv',
                SupportedFileTypes::CSV
            ),
        ]
    );
    $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
});
