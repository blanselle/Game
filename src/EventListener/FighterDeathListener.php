<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FighterDeathListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ('game_init' === $event->getRequest()->get('_route')) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if (
            $user
            && (
                0 >= $user->getHealth()
                || null === $user->getPosition()
            )
        ) {
            if (null !== $event->getRequest()->get('_route')) {
                $event->setResponse(new RedirectResponse($this->urlGenerator->generate('game_init')));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest',0],
        ];
    }
}
