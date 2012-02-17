<?php

namespace Cupon\CiudadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    // realiza cambio de ciudad
    public function cambiarAction($ciudad)
    {
        return new RedirectResponse($this->generateUrl(
            'portada',
            array('ciudad' => $ciudad)
            ));
    }
    
    // devuelve el listado de ciudades
    public function listaCiudadesAction($ciudad)
    {
        // obtiene el entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene el listado de ciudades
        $ciudades = $em->getRepository('CiudadBundle:Ciudad')->findAll();

        // retorna el listado de ciudades (con el formato de "CiudadBundle:Default:listaCiudades.html.twig")
        return $this->render(
            'CiudadBundle:Default:listaCiudades.html.twig',
            array(
                'ciudades' => $ciudades,
                'ciudadActual' => $ciudad
                )
            );
    }
    
    // devuelve la página de ofertas recientes
    public function recientesAction($ciudad)
    {
        // obtiene el entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene ciudad
        $ciudad = $em->getRepository('CiudadBundle:Ciudad')->findOneBySlug($ciudad);
        
        // lanza excepción en caso de que no exista la ciudad
        if (!$ciudad) {
            throw $this->createNotFoundException('No existe la ciudad');
        }
        
        // obtiene cercanas
        $cercanas = $em->getRepository('CiudadBundle:Ciudad')->findCercanas($ciudad->getId());

        // obtiene ofertas recientes
        $ofertas = $em->getRepository('OfertaBundle:Oferta')->findRecientes($ciudad->getId());
        
        // retorna respuesta
        return $this->render(
            'CiudadBundle:Default:recientes.html.twig',
            array('ciudad' => $ciudad,
                'cercanas' => $cercanas,
                'ofertas' => $ofertas,
                )
            );
    }
}
