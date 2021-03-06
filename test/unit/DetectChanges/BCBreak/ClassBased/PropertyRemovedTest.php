<?php

declare(strict_types=1);

namespace RoaveTest\BackwardCompatibility\DetectChanges\BCBreak\ClassBased;

use PHPUnit\Framework\TestCase;
use Roave\BackwardCompatibility\Change;
use Roave\BackwardCompatibility\DetectChanges\BCBreak\ClassBased\PropertyRemoved;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use function array_map;
use function iterator_to_array;

final class PropertyRemovedTest extends TestCase
{
    /**
     * @param string[] $expectedMessages
     *
     * @dataProvider classesToBeTested
     */
    public function testDiffs(
        ReflectionClass $fromClass,
        ReflectionClass $toClass,
        array $expectedMessages
    ) : void {
        $changes = (new PropertyRemoved())
            ->__invoke($fromClass, $toClass);

        self::assertSame(
            $expectedMessages,
            array_map(static function (Change $change) : string {
                return $change->__toString();
            }, iterator_to_array($changes))
        );
    }

    /**
     * @return array<string, array<int, ReflectionClass|array<int, string>>>
     *
     * @psalm-return array<string, array{0: ReflectionClass, 1: ReflectionClass, 2: array<int, string>}>
     */
    public function classesToBeTested() : array
    {
        $locator = (new BetterReflection())->astLocator();

        return [
            'RoaveTestAsset\\ClassWithPropertiesBeingRemoved' => [
                (new ClassReflector(new SingleFileSourceLocator(
                    __DIR__ . '/../../../../asset/api/old/ClassWithPropertiesBeingRemoved.php',
                    $locator
                )))->reflect('RoaveTestAsset\\ClassWithPropertiesBeingRemoved'),
                (new ClassReflector(new SingleFileSourceLocator(
                    __DIR__ . '/../../../../asset/api/new/ClassWithPropertiesBeingRemoved.php',
                    $locator
                )))->reflect('RoaveTestAsset\\ClassWithPropertiesBeingRemoved'),
                [
                    '[BC] REMOVED: Property RoaveTestAsset\ClassWithPropertiesBeingRemoved#$removedPublicProperty was removed',
                    '[BC] REMOVED: Property RoaveTestAsset\ClassWithPropertiesBeingRemoved#$nameCaseChangePublicProperty was removed',
                    '[BC] REMOVED: Property RoaveTestAsset\ClassWithPropertiesBeingRemoved#$removedProtectedProperty was removed',
                    '[BC] REMOVED: Property RoaveTestAsset\ClassWithPropertiesBeingRemoved#$nameCaseChangeProtectedProperty was removed',
                ],
            ],
        ];
    }
}
