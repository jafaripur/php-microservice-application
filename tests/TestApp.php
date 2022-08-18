<?php

declare(strict_types=1);

namespace Tests;

use App\App;

class TestApp extends App
{
    /**
     * Get object from the container.
     *
     * @param string $alias
     *
     * @throws \Throwable
     *
     * @return null|mixed|object
     */
    public function get($alias, string $context = null)
    {
        return $this->container->get($alias, $context);
    }
}
