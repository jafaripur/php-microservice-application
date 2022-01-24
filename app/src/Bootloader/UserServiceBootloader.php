<?php

/**
 * User service implement
 */

declare(strict_types=1);

namespace App\Bootloader;

use Araz\MicroService\Queue;
use Araz\Service\User\UserService;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Core\Container;
use Spiral\Logger\LogsInterface;

final class UserServiceBootloader extends Bootloader
{
    public function boot(Container $container, EnvironmentInterface $env, LogsInterface $logs): void
    {
        $container->bindSingleton(UserService::class, bind(UserService::class, [
            'queue' => new Queue(
                $env->get('APP_NAME'),
                [
                    'dsn' => $env->get('QUEUE_AMQP_DSN'),
                    'lazy' => true,
                    'persisted' => true,
                    'heartbeat' => 10,
                    "qos_prefetch_count" => 1,
                ],
                $env->get('DEBUG') ? $logs->getLogger('default') : $logs->getLogger($env->get('APP_NAME')),
                $container,
                true,
                true,
                [
                    \App\Queue\Consumer\ConsumerFirst::class,
                    \App\Queue\Consumer\ConsumerSecond::class,
                ]
            ),
        ]));
    }
}
