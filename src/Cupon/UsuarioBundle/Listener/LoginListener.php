<?php

// src/Cupon/UsuarioBundle/Listener/LoginListener.php

namespace Cupon\UsuarioBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

class LoginListener {

    private $router = null;
    private $ciudad = null;
    private $contexto = null;

    // constructor
    public function __construct(Router $router, SecurityContext $contexto) {
        $this->router = $router;
        $this->contexto = $contexto;
    }

    // obtiene y almacena la ciudad del usuario que hizo login
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $token = $event->getAuthenticationToken();
        $this->ciudad = $token->getUser()->getCiudad()->getSlug();
    }

    // redirecciona a la ciudad del usuario
    public function onKernelResponse(FilterResponseEvent $event) {
        if (null != $this->ciudad) {
            if ($this->contexto->isGranted('ROLE_TIENDA')) {
                $portada = $this->router->generate('extranet_portada');
            } else {
                $portada = $this->router->generate('portada', array(
                    'ciudad' => $this->ciudad
                        ));
            }
            $event->setResponse(new RedirectResponse($portada));
            $event->stopPropagation();
        }
    }

}