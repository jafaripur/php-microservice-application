<?php

/**
 * User service implement
 */

declare(strict_types=1);

namespace App\Bootloader;

use Araz\MicroService\Queue;
use Araz\Service\User\UserService;
use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Core\Container;
use Spiral\Logger\LogsInterface;

final class UserServiceBootloader extends Bootloader
{
    public function boot(Container $container, EnvironmentInterface $env, LogsInterface $logs): void
    {
        $container->bindSingleton('user-service-queue', bind(Queue::class, [
            'appName' => $env->get('APP_NAME'),
            'transport' => [
                'dsn' => $env->get('QUEUE_AMQP_DSN'),
                'lazy' => true,
                'persisted' => true,
                'heartbeat' => 10,
                "qos_prefetch_count" => 1,
            ],
            'logger' => $env->get('DEBUG') ? $logs->getLogger('default') : $logs->getLogger($env->get('APP_NAME')),
            'container' => $container,
            'enableClient' => true,
            'enableConsumer' => true,
            'processorConsumers' => [
                \App\Queue\Consumer\ConsumerFirst::class,
                \App\Queue\Consumer\ConsumerSecond::class,
            ],
        ]));

        $container->bindSingleton(UserService::class, function () use ($container) {
            return new UserService($container->get('user-service-queue'));
        });
    }
}
