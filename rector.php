<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\Config\RectorConfig;
use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;

try {
    return RectorConfig::configure()
        ->withPaths([
            __DIR__ . '/config',
            __DIR__ . '/src',
        ])
        ->withSets([
            LevelSetList::UP_TO_PHP_84,
            PHPUnitSetList::PHPUNIT_90,
        ])
        ->withPhpSets(php84: true)
        ->withComposerBased(symfony: true)
        ->withAttributesSets(all: true)
        ->withPreparedSets(deadCode: true, codeQuality: true)
        // ->withPreparedSets(typeCoverage: true)
        ->withTypeCoverageLevel(49);
} catch (InvalidConfigurationException $e) {

}
