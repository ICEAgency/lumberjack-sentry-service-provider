<?php
namespace IceAgency\Lumberjack\Providers;

use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Application;
use Raven_Client;
use Raven_ErrorHandler;

class SentryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('raven_client', Raven_Client::class);
        $this->app->bind('raven_error_handler', Raven_ErrorHandler::class);
    }

    public function boot(Config $config)
    {
        if (!$config->get('sentry.dsn') || $config->get('sentry.enabled') == 'false') {
            return;
        }

        $client = new Raven_Client($config->get('sentry.dsn'), [
            'environment' => $config->get('app.environment')
        ]);

        $this->app->bind('raven_client', $client);

        $error_handler = new Raven_ErrorHandler($client);

        $this->app->bind('raven_error_handler', $error_handler);

        $error_handler->registerExceptionHandler();
        $error_handler->registerErrorHandler();
        $error_handler->registerShutdownFunction();
    }
}
