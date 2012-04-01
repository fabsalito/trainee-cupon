<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class ExtranetController extends Controller
{
    public function portadaAction()
    {
        
    }
    
    public function loginAction()
    {
        // obtiene la petición
        $peticion = $this->getRequest();
        
        // obtiene sesión
        $sesion = $peticion->getSession();
        
        // obtiene error
        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );

        // retorna respuesta
        return $this->render('TiendaBundle:Extranet:login.html.twig', array(
            'error' => $error
        ));
    }
}
