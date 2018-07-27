# Sentry Service Provider for Lumberjack

A Service Provider for the [Lumberjack](https://github.com/Rareloop/lumberjack) framework that allows simple integration to [Sentry](https://sentry.io/).

Written & maintained by the team at [The ICE Agency](https://www.theiceagency.co.uk)

Please note, this repo is not ready for production.

## Requirements

* PHP >=7.0
* Installation via Composer
* [Lumberjack](https://github.com/Rareloop/lumberjack)

## Installing

1. Install Lumberjack, see the guide [here](https://github.com/Rareloop/lumberjack).
2. Install via Composer:
```composer require iceagency/lumberjack-sentry-service-provider```


## Getting Started

1. Create a config file called `config/sentry.php`
2. Add the following:
```
<?php
return [
    'enabled' => getenv('SENTRY_ENABLED')
    'dsn' => getenv('SENTRY_DSN'),
];
```
3. Add your Sentry DSN to your .env

```
SENTRY_DSN=https://public:secret@sentry.example.com/1
```
4. Add the Service Provider to the Providers array in `config/app.php` as follows:
```
'providers' => [
    ...
    IceAgency\Lumberjack\Providers\SentryServiceProvider::class,
    ...
],
```

## Usage

By default, Sentry will automatically be sent exceptions, to disable Sentry for a specific environment, add this following to your .env

```
SENTRY_ENABLED=false
```