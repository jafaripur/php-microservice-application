<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use Spiral\Bootloader as Framework;
use Spiral\Framework\Kernel;

class App extends Kernel
{
    protected const APP = [
        \App\Bootloader\MicroServiceQueueBootloader::class,
        \App\Bootloader\UserServiceBootloader::class,
    ];

    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [

        // Environment configuration
        \Spiral\DotEnv\Bootloader\DotenvBootloader::class,

        \App\Bootloader\LoggingBootloader::class,
        \Spiral\Sentry\Bootloader\SentryBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,

        \Spiral\Bootloader\DebugBootloader::class,
        \Spiral\Bootloader\Debug\LogCollectorBootloader::class,

    ];
}
