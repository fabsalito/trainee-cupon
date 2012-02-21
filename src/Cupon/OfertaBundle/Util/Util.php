<?php
// src/Cupon/OfertaBundle/Util/Util.php

namespace Cupon\OfertaBundle\Util;

class Util
{
    private $logger; 
    private $router; 
    private $codificacion;
    
    // constructor
    public function __construct($logger, $router)
    {
        //$this->logger = $logger;
        //$this->router = $router;
    }
    
    // setea codificación
    public function setCodificacion($codificacion)
    {
        $this->codificacion = $codificacion;
    }
    
    // obtiene slug de $cadena
    static public function getSlug($cadena, $separador = '-')
    {
        // Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv($this->codificacion, 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        
        return $slug;
    }
}
