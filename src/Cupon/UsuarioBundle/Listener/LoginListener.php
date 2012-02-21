<?php
// src/Cupon/UsuarioBundle/Listener/LoginListener.php

namespace Cupon\UsuarioBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginListener
{
    private $router;
    private $ciudad = null;
    
    // constructor
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
    // obtiene y almacena la ciudad del usuario que hizo login
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $this->ciudad = $token->getUser()->getCiudad()->getSlug();
    }
    
    // redirecciona a la ciudad del usuario
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (null != $this->ciudad) {
            $portada = $this->router->generate('portada', array(
                'ciudad' => $this->ciudad
            ));
            
            $event->setResponse(new RedirectResponse($portada));
        }
    }
}