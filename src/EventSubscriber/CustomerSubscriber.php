<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Customer;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CustomerSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['saveCreatedDate', EventPriorities::PRE_WRITE],
        ];
    }

    public function saveCreatedDate(ViewEvent $event): void
    {
        $customer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$customer instanceof Customer || Request::METHOD_POST !== $method) {
            return;
        }

        $customer->setCreatedAt(new DateTimeImmutable());
    }
}