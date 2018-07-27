<?php
namespace IceAgency\Lumberjack\Providers;

use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Facades\Config;
use Raven_Client;
use Raven_ErrorHandler;

class SentryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!Config::get('sentry.dsn') || Config::get('sentry.enabled') == 'false') {
            return;
        }

        $client = new Raven_Client(Config::get('sentry.dsn'), [
            'environment' => Config::get('app.environment')
        ]);

        $error_handler = new Raven_ErrorHandler($client);
        $error_handler->registerExceptionHandler();
        $error_handler->registerErrorHandler();
        $error_handler->registerShutdownFunction();
    }
}