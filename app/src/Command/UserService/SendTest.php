<?php

declare(strict_types=1);

namespace App\Command\UserService;

use Araz\Service\User\UserService;
use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class SendTest extends Command
{
    protected const NAME        = 'user-service/send-test';
    protected const DESCRIPTION = 'Send test messages';
    protected const ARGUMENTS   = [
        ['consumers', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Consumer identity to run'],
    ];

    /**
     * This method supports argument injection.
     */
    public function perform(UserService $userService): void
    {
        $this->sprintf("Sending async command to UserService::getUserInformation()\n");
        $userAsyncCommands = $userService->commands()->async(5000)
            ->getUserInformation(['id' => '123'], 'cor-test-1234', 2000)
            ->getUserInformation(['id' => '123'], 'cor-test-1235', 2000)
            ->getUserInformation(['id' => '123'], 'cor-test-1236', 2000);

        $this->sprintf("Sending command to CommandSender::getUserInformation()\n");
        $response = $userService->commands()->getUserInformation(['id' => '123']);
        $this->sprintf(print_r($response->getBody(), true) . "\n\n");

        $this->sprintf("Sending emit to EmitSender::userLoggedIn()\n");
        $msgId = $userService->emits()->userLoggedIn(['id' => '123']);
        $this->sprintf('Emit message ID: %s' . "\n\n", $msgId);


        $this->sprintf("Sending topic to TopicSender::userLoggedIn() with routing key: %s \n", $userService->topics()->getRoutingKeyUserTopicCreate());
        $msgId = $userService->topics()->userChanged($userService->topics()->getRoutingKeyUserTopicCreate(), ['id' => '123']);
        $this->sprintf('Topic message ID: %s' . "\n\n", $msgId);

        $this->sprintf("Sending topic to TopicSender::userLoggedIn() with routing key: %s \n", $userService->topics()->getRoutingKeyUserTopicUpdate());
        $msgId = $userService->topics()->userChanged($userService->topics()->getRoutingKeyUserTopicUpdate(), ['id' => '123']);
        $this->sprintf('Topic message ID: %s' . "\n\n", $msgId);


        $this->sprintf("Sending worker to WorkerSender::userProfileAnalysis() \n");
        $msgId = $userService->workers()->userProfileAnalysis(['id' => '123']);
        $this->sprintf('Worker message ID: %s' . "\n\n", $msgId);

        $this->sprintf("Sending worker to WorkerSender::userProfileUpdateNotification() \n");
        $msgId = $userService->workers()->userProfileUpdateNotification(['id' => '1234']);
        $this->sprintf('Worker message ID: %s' . "\n\n", $msgId);

        $this->sprintf("Receiving async command from UserService::getUserInformation ...");
        foreach ($userAsyncCommands->receive() as $correlationId => $response) {
            $this->sprintf(print_r([$correlationId => $response->getBody()], true) . "\n\n");
        }
    }
}
