<?php
namespace IceAgency\Lumberjack\Providers;

use ReflectionClass;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;

class SentryServiceProvider extends ServiceProvider
{
    public function boot(Config $config)
    {
        if (!Config::get('sentry.dsn')) {
            return;
        }

        $client = new Raven_Client(Config::get('sentry.dsn'));

        $error_handler = new Raven_ErrorHandler($client);
        $error_handler->registerExceptionHandler();
        $error_handler->registerErrorHandler();
        $error_handler->registerShutdownFunction();
    }
}