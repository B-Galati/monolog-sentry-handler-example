<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HubInterface $hub,
    ) {
    }

    #[Route('/log')]
    public function log(): Response
    {
        $this->logger->debug('Debug message');
        $this->logger->info('This is an info log');

        // --Method not found example
//        $this->itIsGoingToFailForSure();

        // --Exception
//        throw new \RuntimeException('Some Sentry SDK test');

        // --Only logs examples
        $this->logger->warning('Warning message');
        $this->logger->error('Error test');
        $this->logger->critical('Critical test');
        $this->logger->alert('Alert message');
        $this->logger->emergency('Emergency 2');

        // --Only Hub
//        $this->hub->captureException(new \Exception('With the Hub capture exception'));

        // --Only SDK
//        \Sentry\captureException(new \Exception('With the SDK capture exception'));

        // --Divide by 0
//        0/0;

        return new Response('OK');
    }
}
