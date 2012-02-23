<?php
// src/Cupon/OfertaBundle/Util/Util.php

namespace Cupon\OfertaBundle\Util;

class Util
{
    private $logger; 
    private $router; 
    private $codificacion;
    
    // setea codificación
    public function setCodificacion($codificacion)
    {
        $this->codificacion = $codificacion;
    }
    
    // obtiene slug de $cadena
    static public function getSlug($cadena, $separador = '-')
    {
        // crea instancia de la clase
        $self = New self();
        
        // Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv($self->codificacion, 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        
        return $slug;
    }
}
