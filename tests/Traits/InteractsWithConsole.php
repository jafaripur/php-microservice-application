<?php

declare(strict_types=1);

namespace Tests\Traits;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Spiral\Console\Console;

trait InteractsWithConsole
{
    public function console(): Console
    {
        return $this->app->get(Console::class);
    }

    public function runCommand(string $command, array $args = []): string
    {
        $input = new ArrayInput($args);
        $output = new BufferedOutput();

        $this->console()->run($command, $input, $output);

        return $output->fetch();
    }

    public function runCommandDebug(string $command, array $args = [], OutputInterface $output = null): string
    {
        $input = new ArrayInput($args);
        $output ??= new BufferedOutput();
        $output->setVerbosity(BufferedOutput::VERBOSITY_VERBOSE);

        $this->console()->run($command, $input, $output);

        return $output->fetch();
    }

    public function runCommandVeryVerbose(string $command, array $args = [], OutputInterface $output = null): string
    {
        $input = new ArrayInput($args);
        $output ??= new BufferedOutput();
        $output->setVerbosity(BufferedOutput::VERBOSITY_DEBUG);

        $this->console()->run($command, $input, $output);

        return $output->fetch();
    }
}
