<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class DefaultController extends Controller
{
    
    public function portadaAction($ciudad, $tienda)
    {
        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();
        
        // obtiene ciudad
        $ciudad = $em->getRepository('CiudadBundle:Ciudad')->findOneBySlug($ciudad);
        
        // obtiene tienda
        $tienda = $em->getRepository('TiendaBundle:Tienda')->findOneBy(array('slug' => $tienda, 
            'ciudad' => $ciudad->getId()
            ));
        
        // verifica si existe tienda
        if (!$tienda) {
            // lanza excepci�n en caso de que no exista
            throw $this->createNotFoundException('No existe esta tienda');
        }
        
        // obtiene ofertas para la tienda
        $ofertas = $em->getRepository('TiendaBundle:Tienda')->findUltimasOfertasPublicadas($tienda->getId());
        
        // obtiene otras ciudades
        $cercanas = $em->getRepository('TiendaBundle:Tienda')->findCercanas($tienda->getSlug(), $tienda->getCiudad()->getSlug());
        
        // obtiene el formato de la consulta
        $formato = $this->get('request')->getRequestFormat();
        
        // retorna respuesta
        return $this->render('TiendaBundle:Default:portada.'.$formato.'.twig', array(
            'tienda' => $tienda,
            'ofertas' => $ofertas,
            'cercanas' => $cercanas
            ));
    }
}
