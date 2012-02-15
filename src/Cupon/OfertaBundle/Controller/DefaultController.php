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
    
    // procesa el detalle de la oferta
    public function ofertaAction($ciudad, $slug)
    {
        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene la oferta especificada por $ciudad y $slug
        $oferta = $em->getRepository('OfertaBundle:Oferta')->findOferta($ciudad, $slug);
        
        // obtiene las ofertas relacionadas
        $relacionadas = $em->getRepository('OfertaBundle:Oferta')->findRelacionadas($ciudad);

        // renderiza el detalle de la oferta
        return $this->render('OfertaBundle:Default:detalle.html.twig', array(
            'oferta' => $oferta,
            'relacionadas' => $relacionadas
            ));
    }
}