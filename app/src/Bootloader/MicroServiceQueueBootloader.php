<?php

/**
 * User service implement.
 */

declare(strict_types=1);

namespace App\Bootloader;

use Araz\MicroService\AmqpConnection;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Core\Container;

final class MicroServiceQueueBootloader extends Bootloader
{
    public function boot(Container $container, EnvironmentInterface $env): void
    {
        $container->bindSingleton('microservice-queue', bind(AmqpConnection::class, [
            'transport' => [
                'dsn' => $env->get('QUEUE_AMQP_DSN'),
                'lazy' => true,
                'persisted' => true,
                'heartbeat' => 10,
                'qos_prefetch_count' => 1,
            ],
        ]));
    }
}
