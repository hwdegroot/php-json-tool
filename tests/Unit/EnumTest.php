<?php

use App\Enum\Enum;
use App\Exceptions\EnumException;
use Illuminate\Support\Str;

it('should throw an exception', function () {
    $this->expectException(EnumException::class);
    $status = TestEnum::create('bla');
});

it('should instantiate a new instance', function () {
    $noTest = TestEnum::create('no-test');
    $this->assertEquals(TestEnum::NO_TEST, $noTest);
    $this->assertInstanceOf(TestEnum::class, $noTest);
});

it('should get all values from the enum', function () {
    $this->assertCount(2, TestEnum::all());
    $this->assertEquals(
        [
            'NO_TEST' => 'no-test',
            'TEST' => 'test',
        ],
        TestEnum::all()
    );
});

it('should get all values', function () {
    $this->assertCount(2, TestEnum::values());
    $this->assertEquals(
        [
            'no-test',
            'test',
        ],
        TestEnum::values()
    );
});

it('should get all keys', function () {
    $this->assertCount(2, TestEnum::keys());
    $this->assertEquals(
        [
            'NO_TEST',
            'TEST',
        ],
        TestEnum::keys()
    );
});

it('should have a value', function () {
    $this->assertTrue(TestEnum::hasValue('test'));
    $this->assertTrue(TestEnum::hasValue(TestEnum::NO_TEST));

    $this->assertFalse(TestEnum::hasValue(Str::uuid()));
});

/**
 * Wrapper class for test purposes.
 */
class TestEnum extends Enum
{
    const NO_TEST = 'no-test';
    const TEST = 'test';
}
