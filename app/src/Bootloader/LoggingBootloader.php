<?php

/**
 * User service implement
 */

declare(strict_types=1);

namespace App\Bootloader;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Monolog\Bootloader\MonologBootloader;

final class LoggingBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        MonologBootloader::class,
    ];

    public function boot(MonologBootloader $monolog, EnvironmentInterface $env): void
    {

        //stderr
        $monolog->addHandler('default', new StreamHandler('php://stdout', $env->get('DEBUG') ? Logger::DEBUG : Logger::WARNING));

        if ($env->get("SENTRY_DSN")) {
            $client = \Sentry\ClientBuilder::create(['dsn' => $env->get("SENTRY_DSN")])->getClient();
            $monolog->addHandler($env->get('APP_NAME'), new \Sentry\Monolog\Handler(new \Sentry\State\Hub($client), Logger::ERROR));
        }
    }
}
