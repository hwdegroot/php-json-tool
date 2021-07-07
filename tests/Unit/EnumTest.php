<?php

declare(strict_types=1);

use App\Enum\Enum;
use App\Exceptions\EnumException;
use Illuminate\Support\Str;

it('should throw an exception', function (): void {
    $this->expectException(EnumException::class);
    $status = TestEnum::create('bla');
});

it('should instantiate a new instance', function (): void {
    $noTest = TestEnum::create('no-test');
    $this->assertEquals(TestEnum::NO_TEST, $noTest);
    $this->assertInstanceOf(TestEnum::class, $noTest);
});

it('should get all values from the enum', function (): void {
    $this->assertCount(2, TestEnum::all());
    $this->assertEquals(
        [
            'NO_TEST' => 'no-test',
            'TEST' => 'test',
        ],
        TestEnum::all()
    );
});

it('should get all values', function (): void {
    $this->assertCount(2, TestEnum::values());
    $this->assertEquals(
        [
            'no-test',
            'test',
        ],
        TestEnum::values()
    );
});

it('should get all keys', function (): void {
    $this->assertCount(2, TestEnum::keys());
    $this->assertEquals(
        [
            'NO_TEST',
            'TEST',
        ],
        TestEnum::keys()
    );
});

it('can be converted to a string value', function (): void {
    $this->assertSame('test', TestEnum::create('test')->jsonSerialize());
});

it('can retrieve the value', function (): void {
    $this->assertSame('test', TestEnum::create('test')->getValue());
});

it('should have a value', function (): void {
    $this->assertTrue(TestEnum::hasValue('test'));
    $this->assertTrue(TestEnum::hasValue(TestEnum::NO_TEST));

    $this->assertFalse(TestEnum::hasValue(Str::uuid()));
});

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
