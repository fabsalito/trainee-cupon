<?php

namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Cupon\OfertaBundle\Entity\OfertaRepository;

class DefaultController extends Controller
{
    public function portadaAction($ciudad = null)
    {
        // verifica si se ha indicado la ciudad
        if (null == $ciudad) {
            // obtiene la ciudad por defecto
            $ciudad = $this->container->getParameter('cupon.ciudad_por_defecto');
            
            // redirecciona a la portada de la ciudad
            return new RedirectResponse($this->generateUrl('portada', array('ciudad' => $ciudad)));
        }

        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();
        
        // busca la oferta del día
        //$hoy = new \DateTime('today');
        $oferta = $em->getRepository('OfertaBundle:Oferta')->findOfertaDelDia($ciudad);
        
        // verifica si se ha encontrado oferta
        if (!$oferta) {
            // lanza excepción indicando que no se ha encontrado oferta
            throw $this->createNotFoundException('No se ha encontrado la oferta del día en la ciudad seleccionada');
        }
        
        // renderiza HTML
        return $this->render(
            'OfertaBundle:Default:portada.html.twig',
            array('oferta' => $oferta)
        );
    }
}