<?php

declare(strict_types=1);

namespace App\Queue\Processor\User\Worker;

use App\Queue\Processor\User\UserWorker;

final class UserProfileUpdateNotificationWorker extends UserWorker
{
    public function execute(mixed $body): void
    {
    }

    public function getJobName(): string
    {
        return 'user_profile_update_notification';
    }
}
