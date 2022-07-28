<?php

declare(strict_types=1);

namespace App;

use Psr\Log\LoggerInterface;
use Sentry\Integration\EnvironmentIntegration;
use Sentry\Integration\FrameContextifierIntegration;
use Sentry\Integration\RequestIntegration;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;
use Sentry\State\Scope;

class SentryFactory
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function create(
        ?string $dsn,
        string $environment,
        string $release,
        string $projectRoot,
        string $cacheDir
    ): HubInterface {
        \Sentry\init([
            'dsn'                  => $dsn ?: null,
            'environment'          => $environment, // I.e.: staging, testing, production, etc.
            'in_app_include'       => [$projectRoot],
            'in_app_exclude'       => [$cacheDir, "$projectRoot/vendor"],
            'prefixes'             => [$projectRoot],
            'release'              => $release,
            'default_integrations' => false,
            'integrations'         => [
                new RequestIntegration(),
                new EnvironmentIntegration(),
                new FrameContextifierIntegration($this->logger),
            ]
        ]);

        $hub = SentrySdk::getCurrentHub();
        $hub->configureScope(static function (Scope $scope): void {
            $scope->setTags([
                'framework'       => 'symfony',
                'symfony_version' => Kernel::VERSION,
            ]);
        });

        return SentrySdk::getCurrentHub();
    }
}

