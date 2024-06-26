<?php

use Lunarstorm\LaravelDDD\Support\DomainResolver;
use Lunarstorm\LaravelDDD\Support\Path;
use Lunarstorm\LaravelDDD\ValueObjects\DomainObject;

it('can create a domain object from resolvable class names', function (string $class, $domain, $relativeNamespace, $objectName) {
    $domainObject = DomainObject::fromClass($class);

    $expectedPath = Path::join(DomainResolver::domainPath(), $domain, $relativeNamespace, $objectName.'.php');

    expect($domainObject)
        ->name->toEqual($objectName)
        ->domain->toEqual($domain)
        ->namespace->toEqual($relativeNamespace)
        ->path->toEqual($expectedPath);
})->with([
    [
        // Full Class Name
        'Domain\Invoicing\Models\Invoice',

        // Domain
        'Invoicing',

        // Object Namespace
        'Models',

        // Object Name
        'Invoice',
    ],

    [
        'Domain\Invoicing\Models\Payment\InvoicePayment',
        'Invoicing',
        'Models',
        'Payment\InvoicePayment',
    ],

    [
        'Domain\Internal\Invoicing\Models\Invoice',
        'Internal\Invoicing',
        'Models',
        'Invoice',
    ],

    [
        'Domain\Internal\Invoicing\Models\Payment\InvoicePayment',
        'Internal\Invoicing',
        'Models',
        'Payment\InvoicePayment',
    ],

    [
        'Domain\Invoicing\AdHoc\Thing',
        'Invoicing',
        '',
        'AdHoc\Thing',
    ],

    [
        'Domain\Invoicing\AdHoc\Nested\Thing',
        'Invoicing',
        '',
        'AdHoc\Nested\Thing',
    ],

    [
        'Domain\Invoicing\InvoicingServiceProvider',
        'Invoicing',
        '',
        'InvoicingServiceProvider',
    ],

    // Ad-hoc objects inside subdomains are not supported for now
    // ['Domain\Internal\Invoicing\AdHoc\Thing', 'Internal\Invoicing', '', 'Adhoc\Thing'],
    // ['Domain\Internal\Invoicing\Deeply\Nested\Adhoc\Thing', 'Internal\Invoicing', '', 'Deeply\Nested\Adhoc\Thing'],
]);

it('cannot create a domain object from unresolvable classes', function (string $class) {
    expect(DomainObject::fromClass($class))->toBeNull();
})->with([
    ['Illuminate\Support\Str'],
    ['NotDomain\Invoicing\Models\InvoicePayment'],
    ['Invoice'],
]);
