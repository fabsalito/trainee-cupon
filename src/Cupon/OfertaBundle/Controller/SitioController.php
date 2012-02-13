<?php

namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SitioController extends Controller
{
    public function estaticaAction($pagina)
    {
        return $this->render('OfertaBundle:Sitio:'.$pagina.'.html.twig');
    }
    
    // FUNCIÓN PARA PRUEBAS VARIAS. BORRAR!!!
    public function pruebaAction()
    {
        $peticion = $this->getRequest();
        
        $baseUrl = $peticion->getBaseUrl();
        $method = $peticion->getMethod();
        $requestUri = $peticion->getRequestUri();
        $host = $peticion->getHost();
        $uri = $peticion->getUri();
        $ip = $peticion->getClientIp(true);
        $language = $peticion->getPreferredLanguage();
        $isSecure = $peticion->isSecure()?1:0;
        $requestFormat = $peticion->getRequestFormat();
        //$mimeType = $peticion->getMimeType();
        $mimeType = 'Nuse';
        $hasSession = $peticion->hasSession();
        
        return $this->render('OfertaBundle:Sitio:prueba.html.twig', array('baseUrl' => $baseUrl, 
            'method' => $method, 
            'requestUri' => $requestUri,
            'host' => $host,
            'uri' => $uri,
            'ip' => $ip,
            'language' => $language,
            'isSecure' => $isSecure,
            'requestFormat' => $requestFormat,
            'mimeType' => $mimeType,
            'hasSession' => $hasSession)
            );
    }
}