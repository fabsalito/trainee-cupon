<?php
// src/Cupon/OfertaBundle/Listener/RequestListener.php

namespace Cupon\OfertaBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    // 
    public function onKernelRequest(GetResponseEvent $event)
    {
        $event->getRequest()->setFormat('pdf', 'application/pdf');
    }
}