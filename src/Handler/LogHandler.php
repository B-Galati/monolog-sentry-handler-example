<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class LogHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HubInterface $hub,
    ) {
    }
    
    public function __invoke(Log $log)
    {
        $this->logger->debug('Debug message: '.$log->message);
        $this->logger->info('This is an info log: '.$log->message);

        // --Method not found example
//        $this->itIsGoingToFailForSure();

        // --Exception
//        throw new \RuntimeException('Some Sentry SDK test: '.$message->message);

        // --Only logs examples
        $this->logger->warning('Warning message: '.$log->message);
        $this->logger->error('Error test: '.$log->message);
        $this->logger->critical('Critical test: '.$log->message);
        $this->logger->alert('Alert message: '.$log->message);
        $this->logger->emergency('Emergency 2: '.$log->message);

        // --Only Hub
//        $this->hub->captureException(new \Exception('With the Hub capture exception'));

        // --Only SDK
//        \Sentry\captureException(new \Exception('With the SDK capture exception'));

        // --Divide by 0
//        0/0;

    }
}
