<?php
namespace IceAgency\Lumberjack\Providers;

use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Application;
use Raven_Client;

class SentryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = $this->app->get(Config::class);

        if (!$config->get('sentry.dsn') || $config->get('sentry.enabled') == "false") {
            return;
        }

        $this->app->bind('sentry', new Raven_Client($config->get('sentry.dsn'), [
            'environment' => $config->get('app.environment')
        ]));

        $this->app->get('sentry')->install();
    }
}
