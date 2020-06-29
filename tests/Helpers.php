<?php

namespace Tests;

use File;
use PHPUnit\Framework\Assert as PHPUnit;
use Str;

/**
 * A basic assert example.
 */
function assertExample(): void
{
    test()->assertTrue(true);
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
