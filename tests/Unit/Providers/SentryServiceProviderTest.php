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

    private function initProvider() {
        $this->config = new Config;
        $this->app = new Application;
        $this->app->bind(Config::class, $this->config);
        $this->provider = new SentryServiceProvider($this->app);
    }

    public function testNoBootWhenNoDsnInConfig()
    {
        $this->initProvider();
        $this->assertNull($this->provider->boot($this->config));
    }

    public function testNoBootWhenSentryDisabledInConfigWithNoDsn()
    {
        $this->initProvider();
        $this->config->set('sentry.enabled', "false");

        $this->assertNull($this->provider->boot($this->config));
    }

    public function testNoBootWhenSentryDisabledInConfigWithDsn()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->config->set('sentry.enabled', "false");

        $this->assertNull($this->provider->boot($this->config));
    }

    public function testSentryClientIsCreated()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);

        $this->provider->boot($this->config);

        $this->assertInstanceOf(Raven_Client::class, $this->provider->getSentryClient());
    }

    public function testSentryErrorHandlerIsCreated()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);

        $this->provider->boot($this->config);

        $this->assertInstanceOf(Raven_ErrorHandler::class, $this->provider->getSentryErrorHandler());
    }

    public function testEnvironmentEmptyWhenNotInConfig()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);

        $this->provider->boot($this->config);

        $this->assertNull($this->provider->getSentryClient()->environment);
    }

    public function testEnvironmentSetWhenInConfig()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->config->set('app.environment', 'production');

        $this->provider->boot($this->config);

        $this->assertEquals($this->provider->getSentryClient()->environment, 'production');
    }
}