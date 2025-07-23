<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ModulesSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $session;

    public function __construct(Environment $twig,   private RequestStack $requestStack,)
    {
        $this->twig = $twig;

    }

    public function injectGlobalVariable(RequestEvent $event)
    {

        $session = $this->requestStack->getSession();;
        $module = $session->get('__modules__');


        $this->twig->addGlobal('__module__',  $module);

    }
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.  KernelEvents::REQUEST => [['onKernelRequest', 20]],
        return [
            KernelEvents::REQUEST => 'injectGlobalVariable',
        ];
    }
}
