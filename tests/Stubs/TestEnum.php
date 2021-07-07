<?php

declare(strict_types=1);

namespace Tests\Stubs;

use App\Enum\Enum;

/**
 * Wrapper class for test purposes.
 *
 * @internal
 */
final class TestEnum extends Enum
{
    const NO_TEST = 'no-test';
    const TEST = 'test';
}
