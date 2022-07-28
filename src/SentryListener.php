<?php
declare(strict_types=1);

namespace App;

use Sentry\State\HubInterface;
use Sentry\State\Scope;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class SentryListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly HubInterface $hub,
        private readonly Security $security,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $userData['ip_address'] = $event->getRequest()->getClientIp();

        if ($user = $this->security->getUser()) {
            $userData['type']     = (new \ReflectionClass($user))->getShortName();
            $userData['username'] = $user->getUserIdentifier();
            $userData['roles']    = $user->getRoles();
        }

        $this->hub->configureScope(static function (Scope $scope) use ($userData): void {
            $scope->setUser($userData);
        });
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $matchedRoute = $event->getRequest()->attributes->get('_route');

        if ($matchedRoute === null) {
            return;
        }

        $this->hub->configureScope(static function (Scope $scope) use ($matchedRoute): void {
            $scope->setTag('route', (string) $matchedRoute);
        });
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $statusCode = $event->getResponse()->getStatusCode();

        $this->hub->configureScope(static function (Scope $scope) use ($statusCode): void {
            $scope->setTag('status_code', (string) $statusCode);
        });
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand()?->getName() ?? 'N/A';

        $this->hub->configureScope(static function (Scope $scope) use ($command): void {
            $scope->setTag('command', $command);
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST    => ['onKernelRequest', 1],
            KernelEvents::CONTROLLER => ['onKernelController', 10000],
            KernelEvents::TERMINATE  => ['onKernelTerminate', 1],
            ConsoleEvents::COMMAND   => ['onConsoleCommand', 1],
        ];
    }
}
