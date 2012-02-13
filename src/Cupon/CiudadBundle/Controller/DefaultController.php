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
}
