<?php
namespace IceAgency\Lumberjack\Providers;

use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;
use Raven_Client;
use Raven_ErrorHandler;

class SentryServiceProvider extends ServiceProvider
{
    private $sentry_client;
    private $sentry_error_handler;

    public function boot(Config $config)
    {
        if (!$config->get('sentry.dsn') || $config->get('sentry.enabled') == 'false') {
            return;
        }

        $this->sentry_client = new Raven_Client($config->get('sentry.dsn'), [
            'environment' => $config->get('app.environment')
        ]);

        $this->sentry_error_handler = new Raven_ErrorHandler($this->sentry_client);
        $this->sentry_error_handler->registerExceptionHandler();
        $this->sentry_error_handler->registerErrorHandler();
        $this->sentry_error_handler->registerShutdownFunction();
    }

    public function getSentryClient() : Raven_Client {
        return $this->sentry_client;
    }

    public function getSentryErrorHandler() : Raven_ErrorHandler {
        return $this->sentry_error_handler;
    }
}