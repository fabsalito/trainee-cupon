<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\UsuarioBundle\Entity\Usuario;

class DefaultController extends Controller
{
    // renderiza formulario de registro
    public function registroAction()
    {
        // crea nuevo objeto usuario
        $usuario = new Usuario();

        // crea formulario
        $formulario = $this->createFormBuilder($usuario)
            ->add('nombre')
            ->add('apellidos')
            ->add('direccion', 'text')
            ->add('fechaNacimiento', 'date')
            ->getForm();

        // retorna respuesta
        return $this->render('UsuarioBundle:Default:registro.html.twig',
            array('formulario' => $formulario->createView())
        );
    }

    // compras del usuario
    public function comprasAction()
    {
        // obtiene ID del usuario logeado
        $usuario_id = $this->get('security.context')->getToken()->getUser()->getId();

        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene las últimas compras del usuario logeado
        $compras = $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario_id);

        // retorna respuesta
        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
            'compras' => $compras
        ));
    }
    
    // formulario login
    public function loginAction()
    {
        // obtiene la petición
        $peticion = $this->getRequest();
        
        // obtiene sesión
        $sesion = $peticion->getSession();
        
        // obtiene el error
        $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
    
        // retorna respuesta
        return $this->render('UsuarioBundle:Default:login.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        ));
    }
    
    // (mini) formulario login
    public function cajaLoginAction()
    {
        // obtiene la petición
        $peticion = $this->getRequest();
        
        // obtiene sesión
        $sesion = $peticion->getSession();
        
        // obtiene el error
        $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        
        // obtiene usuario
        $usuario = $this->get('security.context')->getToken()->getUser();
    
        // retorna respuesta
        return $this->render('UsuarioBundle:Default:cajaLogin.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
            'usuario' => $usuario
        ));
    }
}
