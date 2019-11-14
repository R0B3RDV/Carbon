<?php

namespace Carbon\PHPStan;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Closure;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\BooleanType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use ReflectionClass;

class MacroExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getTypeFromStaticMethodCall(MethodReflection $methodReflection, StaticCall $methodCall, Scope $scope): Type
    {
        $classReflection = $methodReflection->getDeclaringClass();
        $className = $classReflection->getName();
        $methodName = $methodReflection->getName();
        var_dump($className, $methodName);
        exit;

        if (method_exists($className, $methodName)) {
            return $methodCall->getType();
        }

        $reflectionClass = new ReflectionClass($className);
        $property = $reflectionClass->getProperty('globalMacros');

        return new BooleanType();

        $property->setAccessible(true);
        /** @var Closure $function */
        $function = $property->getValue()[$methodName];
        var_dump($function);
        exit;

        // return $function->;
        $rawType = preg_replace('/\|null$/', '', $this->types[$methodReflection->getName()]);

        switch ($rawType) {
            case 'bool':
                return new BooleanType();
            case 'self':
                return new ObjectType($this->getClass());
        }

        return new MixedType();
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        $classReflection = $methodReflection->getDeclaringClass();
        var_dump($classReflection->getName(), $methodReflection->getName());

        if (!in_array(
            CarbonInterface::class,
            $classReflection->getInterfaces()
        )) {
            /** @var CarbonInterface $class */
            $class = $classReflection->getName();
            $name = $methodReflection->getName();

            return $class::hasMacro($name);
        }

        return false;
    }

    public function getClass(): string
    {
        return CarbonInterface::class;
    }
}
