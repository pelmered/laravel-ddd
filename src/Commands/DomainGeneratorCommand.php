<?php

namespace Lunarstorm\LaravelDDD\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Lunarstorm\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;
use Lunarstorm\LaravelDDD\Commands\Concerns\ResolvesStubPath;
use Lunarstorm\LaravelDDD\Support\DomainResolver;

abstract class DomainGeneratorCommand extends GeneratorCommand
{
    use ResolvesDomainFromInput;
    use ResolvesStubPath;

    protected function getRelativeDomainNamespace(): string
    {
        return DomainResolver::getRelativeObjectNamespace($this->guessObjectType());
    }

    protected function getNameInput()
    {
        return Str::studly($this->argument('name'));
    }

    protected function fillPlaceholder($stub, $placeholder, $value)
    {
        return str_replace(["{{$placeholder}}", "{{ $placeholder }}"], $value, $stub);
    }

    protected function preparePlaceholders(): array
    {
        return [];
    }

    protected function applyPlaceholders($stub)
    {
        $placeholders = $this->preparePlaceholders();

        foreach ($placeholders as $placeholder => $value) {
            $stub = $this->fillPlaceholder($stub, $placeholder, $value ?? '');
        }

        return $stub;
    }

    protected function buildClass($name)
    {
        return $this->applyPlaceholders(parent::buildClass($name));
    }
}
