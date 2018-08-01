<?php

namespace IceAgency\Lumberjack\Test\Unit\Providers;

use ReflectionClass;
use Raven_Client;
use Raven_ErrorHandler;
use PHPUnit\Framework\TestCase;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;
use IceAgency\Lumberjack\Providers\SentryServiceProvider;

class SentryServiceProviderTest extends TestCase
{
    private $exampleDsn = 'https://<key>@sentry.io/<project>';
    private $provider;
    private $config;
    private $app;

    private function initProvider()
    {
        $this->config = new Config;
        $this->app = new Application;
        $this->app->bind(Config::class, $this->config);
        $this->provider = new SentryServiceProvider($this->app);
    }

    public function testNoRegisterWhenNoDsnInConfig()
    {
        $this->initProvider();
        $this->assertNull($this->provider->register());
    }

    public function testNoRegisterWhenSentryDisabledInConfigWithNoDsn()
    {
        $this->initProvider();
        $this->config->set('sentry.enabled', "false");

        $this->assertNull($this->provider->register());
    }

    public function testNoRegisterWhenSentryDisabledInConfigWithDsn()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->config->set('sentry.enabled', "false");

        $this->assertNull($this->provider->register());
    }

    public function testSentryClientIsCreated()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);

        $this->provider->register();

        $this->assertInstanceOf(Raven_Client::class, $this->app->get(Raven_Client::class));
    }

    public function testEnvironmentSetWhenInConfig()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->config->set('app.environment', 'production');

        $this->provider->register();

        $this->assertEquals($this->app->get(Raven_Client::class)->environment, 'production');
    }

    public function testEnvironmentEmptyWhenNotInConfig()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);

        $this->provider->register();

        $this->assertNull($this->app->get(Raven_Client::class)->environment);
    }
}
