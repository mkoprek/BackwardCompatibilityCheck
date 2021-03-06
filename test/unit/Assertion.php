<?php

declare(strict_types=1);

namespace RoaveTest\BackwardCompatibility;

use PHPUnit\Framework\Assert;
use ReflectionProperty;
use Roave\BackwardCompatibility\Changes;
use function count;

abstract class Assertion
{
    /** @var ReflectionProperty|null */
    private static $unBufferedChangesReflection;

    final private function __construct()
    {
    }

    public static function assertChangesEqual(
        Changes $expected,
        Changes $actual,
        string $message = ''
    ) : void {
        Assert::assertNotNull(
            self::reflectionUnBufferedChanges()->getValue($actual),
            'Buffer must NOT be exhausted'
        );
        // Forces eager initialisation of the `Changes` instances, allowing us to compare them by value
        Assert::assertCount(count($expected), $actual);
        Assert::assertEquals($expected, $actual, $message);
    }

    private static function reflectionUnBufferedChanges() : ReflectionProperty
    {
        if (self::$unBufferedChangesReflection) {
            return self::$unBufferedChangesReflection;
        }

        self::$unBufferedChangesReflection = new ReflectionProperty(Changes::class, 'unBufferedChanges');

        self::$unBufferedChangesReflection->setAccessible(true);

        return self::$unBufferedChangesReflection;
    }
}
