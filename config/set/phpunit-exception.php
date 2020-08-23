<?php

declare(strict_types=1);

use Rector\PHPUnit\Rector\ClassMethod\ExceptionAnnotationRector;
use Rector\PHPUnit\Rector\MethodCall\DelegateExceptionArgumentsRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_objects;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    # handles 2nd and 3rd argument of setExpectedException
    $services->set(DelegateExceptionArgumentsRector::class);

    $services->set(ExceptionAnnotationRector::class);

    $configuration = [
        new MethodCallRename('PHPUnit\Framework\TestClass', 'setExpectedException', 'expectedException'),
        new MethodCallRename('PHPUnit\Framework\TestClass', 'setExpectedExceptionRegExp', 'expectedException'),
    ];

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::OLD_TO_NEW_METHODS_BY_CLASS => inline_objects($configuration),
        ]]);
};
