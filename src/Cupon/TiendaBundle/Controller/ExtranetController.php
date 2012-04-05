<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class ExtranetController extends Controller
{
    // controlador para el listado de ventas para una ofertaVentasAction
    public function ofertaVentasAction ($id) {
        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene las ventas para la oferta $id
        $ventas = $em->getRepository('OfertaBundle:Oferta')
                     ->findVentasByOferta($id);

        // devuelve response
        return $this->render('TiendaBundle:Extranet:ventas.html.twig', array(
            'oferta' => $ventas[0]->getOferta(),
            'ventas' => $ventas
        ));
    }

    // controlador para la portada de la extranet
    public function portadaAction()
    {
        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene tienda logeada
        $tienda = $this->get('security.context')->getToken()->getUser();

        // obtiene el listado de ofertas de la tienda
        $ofertas = $em->getRepository('TiendaBundle:Tienda')
                      ->findOfertasRecientes($tienda->getId());

        // devuelve response
        return $this->render('TiendaBundle:Extranet:portada.html.twig', array(
            'ofertas' => $ofertas
        ));
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

