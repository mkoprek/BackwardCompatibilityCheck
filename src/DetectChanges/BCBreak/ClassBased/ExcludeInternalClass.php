<?php

declare(strict_types=1);

namespace Roave\BackwardCompatibility\DetectChanges\BCBreak\ClassBased;

use Roave\BackwardCompatibility\Changes;
use Roave\BetterReflection\Reflection\ReflectionClass;
use function Safe\preg_match;

/**
 * Classes marked "internal" (docblock) are not affected by BC checks.
 */
final class ExcludeInternalClass implements ClassBased
{
    /** @var ClassBased */
    private $check;

    public function __construct(ClassBased $check)
    {
        $this->check = $check;
    }

    public function __invoke(ReflectionClass $fromClass, ReflectionClass $toClass) : Changes
    {
        if ($this->isInternalDocComment($fromClass->getDocComment())) {
            return Changes::empty();
        }

        return $this->check->__invoke($fromClass, $toClass);
    }

    private function isInternalDocComment(string $comment) : bool
    {
        return preg_match('/\s+@internal\s+/', $comment) === 1;
    }
}
