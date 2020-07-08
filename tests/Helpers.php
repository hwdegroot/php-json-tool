<?php

namespace Tests;

use File;
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

function assertSnapshotEquals(string $snapshotLocation, $response)
{
    $snapshot = base_path(
        env('SNAPSHOT_LOCATION', 'tests/__snapshots__')
        .Str::start($snapshotLocation, '/')
    );
    $responseContents = file_get_contents($response->getFile());

    PHPUnit::assertEquals(
        File::get($snapshot),
        $responseContents,
        "The response was not the expected shapshot: {$snapshotLocation}"
    );
}
