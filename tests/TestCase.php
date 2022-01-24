<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment;
use Spiral\Files\Files;
use Tests\Traits\InteractsWithConsole;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithConsole;

    /** @var \Spiral\Boot\AbstractKernel */
    protected $app;

    protected function setUp(): void
    {
        $this->app = $this->makeApp();
    }

    protected function tearDown(): void
    {
        $fs = new Files();

        $runtime = $this->app->get(DirectoriesInterface::class)->get('runtime');
        if ($fs->isDirectory($runtime)) {
            $fs->deleteDirectory($runtime);
        }
    }

    protected function makeApp(array $env = []): TestApp
    {
        $root = dirname(__DIR__);

        return TestApp::init([
            'root' => $root,
            'app' => $root . '/app',
            'runtime' => $root . '/runtime/tests',
            'cache' => $root . '/runtime/tests/cache',
        ], new Environment($env), false);
    }
}
