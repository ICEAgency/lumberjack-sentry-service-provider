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
        $config = $this->app->get(Config::class);

        if (!$config->get('sentry.dsn') || $config->get('sentry.enabled') == "false") {
            return;
        }

        $this->app->bind(Raven_Client::class, new Raven_Client($config->get('sentry.dsn'), [
            'environment' => $config->get('app.environment')
        ]));

        $this->app->bind(Raven_ErrorHandler::class, new Raven_ErrorHandler($this->app->get(Raven_Client::class)));
    }

    public function boot()
    {
        $raven_error_handler = $this->app->get(Raven_ErrorHandler::class);
        $raven_error_handler->registerExceptionHandler();
        $raven_error_handler->registerErrorHandler();
        $raven_error_handler->registerShutdownFunction();
        return true;
    }
}
