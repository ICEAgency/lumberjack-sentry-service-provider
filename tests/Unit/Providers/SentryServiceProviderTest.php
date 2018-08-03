<?php

namespace IceAgency\Lumberjack\Test\Unit\Providers;

use Raven_Client;
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

    public function testIsBound()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->provider->register();
        $this->assertTrue($this->app->has('sentry'));
        $this->assertInstanceOf(Raven_Client::class, $this->app->get('sentry'));
    }

    public function testNoDsn()
    {
        $this->initProvider();
        $this->provider->register();
        $this->assertFalse($this->app->has('sentry'));
    }

    public function testDisabled()
    {
        $this->initProvider();
        $this->config->set('sentry.enabled', "false");
        $this->provider->register();
        $this->assertFalse($this->app->has('sentry'));
    }

    public function testEnvironment()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->config->set('app.environment', 'production');
        $this->provider->register();
        $this->assertEquals($this->app->get('sentry')->environment, 'production');
    }

    public function testNoEnvironment()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->provider->register();
        $this->assertNull($this->app->get('sentry')->environment);
    }

    public function testDsn()
    {
        $this->initProvider();
        $this->config->set('sentry.dsn', $this->exampleDsn);
        $this->provider->register();
        $this->assertEquals($this->app->get('sentry')->server, 'https://sentry.io/api/<project>/store/');
        $this->assertEquals($this->app->get('sentry')->public_key, '<key>');
        $this->assertEquals($this->app->get('sentry')->project, '<project>');
    }
}
