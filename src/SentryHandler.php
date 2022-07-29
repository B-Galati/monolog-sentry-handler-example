<?php

declare(strict_types=1);

namespace App;

use BGalati\MonologSentryHandler\SentryHandler as BaseSentryHandler;
use Sentry\Event as SentryEvent;
use Sentry\State\Scope;

class SentryHandler extends BaseSentryHandler
{
    /** {@inheritdoc} */
    protected function processScope(Scope $scope, $record, SentryEvent $sentryEvent): void
    {
        $scope->setExtra('processScope', 'value');
        $scope->setTag('processScope', 'value');
    }

    /** {@inheritdoc} */
    protected function afterWrite(): void
    {
        parent::afterWrite();
    }
}
