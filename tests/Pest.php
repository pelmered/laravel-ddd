<?php

use Lunarstorm\LaravelDDD\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function skipOnLaravelVersionsBelow($minimumVersion)
{
    $version = app()->version();

    if (version_compare($version, $minimumVersion, '<')) {
        test()->markTestSkipped("Only relevant from Laravel {$minimumVersion} onwards (Current version: {$version}).");
    }
}

function setConfigValues(array $values)
{
    TestCase::configValues($values);
}
