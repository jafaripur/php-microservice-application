<?php

declare(strict_types=1);

namespace App\Command\UserService;

use Araz\Service\User\UserService;
use Psr\Container\ContainerInterface;
use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Listen extends Command
{
    protected const NAME        = 'user-service/listen';
    protected const DESCRIPTION = 'Listen on user service queue and start consumer';
    protected const ARGUMENTS   = [
        ['consumers', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Consumer identity to run'],
    ];

    /**
     * This method supports argument injection.
     */
    public function perform(ContainerInterface $container): void
    {
        $container->get('user-service-queue')->getConsumer()->consume(0, (array)$this->argument('consumers'));
    }
}
