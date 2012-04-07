<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\TiendaBundle\Form\Extranet\TiendaType;

class ExtranetController extends Controller
{
    // perfil de la tienda
    public function perfilAction()
    {
        // obtiene petición
        $peticion = $this->getRequest();

        // crea objeto a relacionar con formulario
        $tienda = $this->get('security.context')->getToken()->getUser();

        // crea formulario
        $formulario = $this->createForm(new TiendaType(), $tienda);

        // verifica método de llamada
        if ('POST' == $peticion->getMethod()) {
            // guarda contraseña original
            $passwordOriginal = $tienda->getPassword();

            // bindea los datos de la petición
            $formulario->bindRequest($peticion);

            // valida formulario
            if ($formulario->isValid()) {
                // procesa password
                if (null == $tienda->getPassword()){
                    // almacena password original
                    $tienda->setPassword($passwordOriginal);
                }
                else {
                    // obtiene encoder
                    $encoder = $this->get('security.encoder_factory')->getEncoder($tienda);
                    // codifica password
                    $passwordCodificado = $encoder->encodePassword(
                        $tienda->getPassword(),
                        $tienda->getSalt()
                    );
                    // almacena nuevo password
                    $tienda->setPassword($passwordCodificado);

                }

                // obtiene entity manager
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($tienda);

                $em->flush();

                $this->get('session')->setFlash('info',
                    'Los datos de tu perfil se han actualizado correctamente'
                );

               // redirecciona
               return $this->redirect($this->generateUrl('extranet_portada'));
            }
        }

        return $this->render('TiendaBundle:Extranet:perfil.html.twig', array(
                'tienda' => $tienda,
                'formulario' => $formulario->createView()
        ));
    }

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

