<?php

namespace Cupon\OfertaBundle\Twig\Extension;

class CuponExtension extends \Twig_Extension
{
    // funciones de la extensi�n
    public function getFunctions()
    {
        return array('descuento' => new \Twig_Function_Method($this, 'descuento'));
    }
    
    // filtros de la extensi�n
    public function getFilters()
    {
        return array('mostrar_como_lista' => new \Twig_Filter_Method($this, 'mostrarComoLista', array('is_safe' => array('html'))),
            'cuenta_atras' => new \Twig_Filter_Method($this, 'cuentaAtras', array('is_safe' => array('html'))),
            );
    }
    
    // cuenta regresiva
    public function cuentaAtras($fecha)
    {
        // En JavaScript los meses empiezan a contar en 0 y acaban en 12
        // En PHP los meses van de 1 a 12, por lo que hay que convertir la fecha
        $fecha = json_encode(array(
            'ano' => $fecha->format('Y'),
            'mes' => $fecha->format('m')-1,
            'dia' => $fecha->format('d'),
            'hora' => $fecha->format('H'),
            'minuto' => $fecha->format('i'),
            'segundo' => $fecha->format('s')
        ));
        
        $idAleatorio = 'cuenta-atras-'.rand(1, 100000);
        
        $html = <<<EOJ
            <span id="$idAleatorio"></span>
            
            <script type="text/javascript">
                window.addEventListener('load', function(){
                    var expira = $fecha;
                    muestraCuentaAtras('$idAleatorio', expira);
                });
            </script>
EOJ;

        return $html;
    }
    
    // muestra un texto como lista <li></li>
    public function mostrarComoLista($value, $tipo='ul')
    {
        $html = "<".$tipo.">\n";
        $html .= " <li>".str_replace("\n", "</li>\n <li>", $value)."</li>\n";
        $html .= "</".$tipo.">\n";
        
        return $html;
    }
    
    // muestra descuento porcentual
    public function descuento($precio, $descuento, $decimales = 0)
    {
        if (!is_numeric($precio) || !is_numeric($descuento)) {
            return '-';
        }
        
        if ($descuento == 0 || $descuento == null) {
            return '0%';
        }
    
        $precio_original = $precio + $descuento;
        $porcentaje = ($descuento / $precio_original) * 100;
    
        return '-'.number_format($porcentaje, $decimales).'%';
    }
    
    // nombre de la extensi�n
    public function getName()
    {
        return 'cupon';
    }
}