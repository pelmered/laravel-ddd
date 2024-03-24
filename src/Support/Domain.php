<?php

namespace Lunarstorm\LaravelDDD\Support;

use Lunarstorm\LaravelDDD\ValueObjects\DomainNamespaces;
use Lunarstorm\LaravelDDD\ValueObjects\DomainObject;

class Domain
{
    public readonly string $dotName;

    public readonly string $path;

    public readonly string $domain;

    public readonly ?string $subdomain;

    public readonly string $domainWithSubdomain;

    public readonly DomainNamespaces $namespace;

    public function __construct(string $domain, ?string $subdomain = null)
    {
        if (is_null($subdomain)) {
            // If a subdomain isn't explicitly specified, we
            // will attempt to parse it from the domain.
            $parts = str($domain)
                ->replace(['\\', '/'], '.')
                ->explode('.')
                ->filter();

            $domain = $parts->shift();

            if ($parts->count() > 0) {
                $subdomain = $parts->implode('.');
            }
        }

        $domain = str($domain)->trim('\\/')->toString();

        $subdomain = str($subdomain)->trim('\\/')->toString();

        $this->domainWithSubdomain = str($domain)
            ->when($subdomain, fn ($domain) => $domain->append("\\{$subdomain}"))
            ->toString();

        $this->domain = $domain;

        $this->subdomain = $subdomain ?: null;

        $this->dotName = $this->subdomain
            ? "{$this->domain}.{$this->subdomain}"
            : $this->domain;

        $this->namespace = DomainNamespaces::from($this->domain, $this->subdomain);

        $this->path = Path::join(DomainResolver::getConfiguredDomainPath(), $this->domainWithSubdomain);
    }

    protected function getDomainBasePath()
    {
        return app()->basePath(DomainResolver::getConfiguredDomainPath());
    }

    public function path(?string $path = null): string
    {
        if (is_null($path)) {
            return $this->path;
        }

        $path = str($path)
            ->replace($this->namespace->root, '')
            ->replace(['\\', '/'], DIRECTORY_SEPARATOR)
            ->append('.php')
            ->toString();

        return Path::join($this->path, $path);
    }

    public function relativePath(string $path = ''): string
    {
        return collect([$this->domain, $path])->filter()->implode(DIRECTORY_SEPARATOR);
    }

    public function model(string $name): DomainObject
    {
        $name = str_replace($this->namespace->models.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->models,
            fqn: $this->namespace->models.'\\'.$name,
            path: $this->path($this->namespace->models.'\\'.$name),
        );
    }

    public function factory(string $name): DomainObject
    {
        $name = str_replace($this->namespace->factories.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->factories,
            fqn: $this->namespace->factories.'\\'.$name,
            path: str("database/factories/{$this->domainWithSubdomain}/{$name}.php")
                ->replace(['\\', '/'], DIRECTORY_SEPARATOR)
                ->toString()
        );
    }

    public function dataTransferObject(string $name): DomainObject
    {
        $name = str_replace($this->namespace->dataTransferObjects.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->dataTransferObjects,
            fqn: $this->namespace->dataTransferObjects.'\\'.$name,
            path: $this->path($this->namespace->dataTransferObjects.'\\'.$name),
        );
    }

    public function dto(string $name): DomainObject
    {
        return $this->dataTransferObject($name);
    }

    public function viewModel(string $name): DomainObject
    {
        $name = str_replace($this->namespace->viewModels.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->viewModels,
            fqn: $this->namespace->viewModels.'\\'.$name,
            path: $this->path($this->namespace->viewModels.'\\'.$name),
        );
    }

    public function valueObject(string $name): DomainObject
    {
        $name = str_replace($this->namespace->valueObjects.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->valueObjects,
            fqn: $this->namespace->valueObjects.'\\'.$name,
            path: $this->path($this->namespace->valueObjects.'\\'.$name),
        );
    }

    public function action(string $name): DomainObject
    {
        $name = str_replace($this->namespace->actions.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->actions,
            fqn: $this->namespace->actions.'\\'.$name,
            path: $this->path($this->namespace->actions.'\\'.$name),
        );
    }

    public function cast(string $name): DomainObject
    {
        $name = str_replace($this->namespace->casts.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->casts,
            fqn: $this->namespace->casts.'\\'.$name,
            path: $this->path($this->namespace->casts.'\\'.$name),
        );
    }

    public function command(string $name): DomainObject
    {
        $name = str_replace($this->namespace->commands.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->commands,
            fqn: $this->namespace->commands.'\\'.$name,
            path: $this->path($this->namespace->commands.'\\'.$name),
        );
    }

    public function enum(string $name): DomainObject
    {
        $name = str_replace($this->namespace->enums.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->enums,
            fqn: $this->namespace->enums.'\\'.$name,
            path: $this->path($this->namespace->enums.'\\'.$name),
        );
    }

    public function job(string $name): DomainObject
    {
        $name = str_replace($this->namespace->jobs.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->jobs,
            fqn: $this->namespace->jobs.'\\'.$name,
            path: $this->path($this->namespace->jobs.'\\'.$name),
        );
    }

    public function mail(string $name): DomainObject
    {
        $name = str_replace($this->namespace->mail.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->mail,
            fqn: $this->namespace->mail.'\\'.$name,
            path: $this->path($this->namespace->mail.'\\'.$name),
        );
    }

    public function notification(string $name): DomainObject
    {
        $name = str_replace($this->namespace->notifications.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->notifications,
            fqn: $this->namespace->notifications.'\\'.$name,
            path: $this->path($this->namespace->notifications.'\\'.$name),
        );
    }

    public function resource(string $name): DomainObject
    {
        $name = str_replace($this->namespace->resources.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->resources,
            fqn: $this->namespace->resources.'\\'.$name,
            path: $this->path($this->namespace->resources.'\\'.$name),
        );
    }

    public function rule(string $name): DomainObject
    {
        $name = str_replace($this->namespace->rules.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->rules,
            fqn: $this->namespace->rules.'\\'.$name,
            path: $this->path($this->namespace->rules.'\\'.$name),
        );
    }

    public function event(string $name): DomainObject
    {
        $name = str_replace($this->namespace->events.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->events,
            fqn: $this->namespace->events.'\\'.$name,
            path: $this->path($this->namespace->events.'\\'.$name),
        );
    }

    public function exception(string $name): DomainObject
    {
        $name = str_replace($this->namespace->exceptions.'\\', '', $name);

        return new DomainObject(
            name: $name,
            namespace: $this->namespace->exceptions,
            fqn: $this->namespace->exceptions.'\\'.$name,
            path: $this->path($this->namespace->exceptions.'\\'.$name),
        );
    }
}
