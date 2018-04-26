<?php

declare(strict_types=1);

namespace Roave\ApiCompare\Comparator\BackwardsCompatibility\MethodBased;

use Roave\ApiCompare\Changes;
use Roave\ApiCompare\Comparator\BackwardsCompatibility\FunctionBased\FunctionBased;
use Roave\BetterReflection\Reflection\ReflectionMethod;

/**
 * Performs a function BC compliance check on a method
 */
final class MethodFunctionDefinitionChanged implements MethodBased
{
    /** @var FunctionBased */
    private $functionCheck;

    public function __construct(FunctionBased $functionCheck)
    {
        $this->functionCheck = $functionCheck;
    }

    public function compare(ReflectionMethod $fromMethod, ReflectionMethod $toMethod) : Changes
    {
        return $this->functionCheck->compare($fromMethod, $toMethod);
    }
}