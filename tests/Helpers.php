<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * A basic assert example.
 */
function assertExample(): void
{
    test()->assertTrue(true);
}

function log(...$messages): void
{
    Log::error(...$messages);
}

function assertSnapshotEquals(string $snapshotLocation, $response): void
{
    $snapshot = base_path(
        env('SNAPSHOT_LOCATION', 'tests/__snapshots__')
        .Str::start($snapshotLocation, '/')
    );
    $responseContents = file_get_contents($response->getFile()->getRealPath());

    PHPUnit::assertEquals(
        File::get($snapshot),
        $responseContents,
        "The response was not the expected shapshot: {$snapshotLocation}"
    );
}
