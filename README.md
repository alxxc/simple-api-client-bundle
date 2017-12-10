# SimpleApiClientBundle
[![Build Status](https://travis-ci.org/alxxc/simple-api-client-bundle.svg?branch=master)](https://travis-ci.org/alxxc/simple-api-client-bundle)

## About

Symfony3 bundle for getting JSON-encoded locations data stored in predefined format.

## Installation

Require the `alxxc/simple-api-client-bundle` package in your composer.json and update your dependencies.

    $ composer require alxxc/simple-api-client-bundle:dev-master

Add the SimpleApiClientBundle to your application's kernel (if needed):

```php
    public function registerBundles()
    {
        $bundles = [
            ...
            new SimpleApiClientBundle\SimpleApiClientBundle(),
            ...
        ];
        ...
    }
```

## Usage

Bundle provides service simple_api_client.client for loading locations data

```php
    $container->get('simple_api_client.client')->loadLocations($url);
```
