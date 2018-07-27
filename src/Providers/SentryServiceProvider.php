<?php
namespace IceAgency\Lumberjack\Providers;

use ReflectionClass;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\Config;

class SentryServiceProvider extends ServiceProvider
{
    public function boot(Config $config)
    {
        dd($config);

    }
}