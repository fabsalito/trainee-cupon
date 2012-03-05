<?php
// src/Cupon/OfertaBundle/Tests/Twig/Extension/CuponExtensionTest.php

namespace Cupon\OfertaBundle\Tests\Twig\Extension;

use Cupon\OfertaBundle\Twig\Extension\CuponExtension;

class TwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    // test para descuento
    public function testDescuento()
    {
        // crea una instancia de la extensión
        $extension = new CuponExtension();
        
        // prueba el descuento null
        $this->assertEquals('-', $extension->descuento(100, null),
            'El descuento no puede ser null'
        );
        
        // prueba que el precio sea numérico
        $this->assertEquals('-', $extension->descuento('a', 3),
            'El precio debe ser un número'
        );
        
        // prueba que el descuento sea numérico
        $this->assertEquals('-', $extension->descuento(100, 'a'),
            'El descuento debe ser un número'
        );
        
        // prueba que un descuento de 0 euros se muestre como 0%
        $this->assertEquals('0%', $extension->descuento(10, 0),
            'Un descuento de cero euros se muestra como 0%'
        );
        
        // prueba que un descuento de 8 sobre un precio de 10 (2+8) se muestra como -80%
        $this->assertEquals('-80%', $extension->descuento(2, 8),
            'Si el precio de venta son 2 euros y el descuento sobre el precio
            original son 8 euros, el descuento es -80%'
        );
        
        // prueba que un decuento de 5 sobre un precio de 15 (10+5) se muestra como -33%
        $this->assertEquals('-33%', $extension->descuento(10, 5),
            'Si el precio de venta son 10 euros y el descuento sobre el precio
            original son 5 euros, el descuento es -33%'
        );
        
        // prueba que un descuento de 5 sobre un precio de 15 (10+5) con 2 decimales se muestra como -33.33%
        $this->assertEquals('-33.33%', $extension->descuento(10, 5, 2),
            'Si el precio de venta son 10 euros y el descuento sobre el precio
            original son 5 euros, el descuento es -33.33% con dos decimales'
        );
    }
    
    // test para conversión a lista ul de saltos de línea
    public function testMostrarComoLista()
    {
        //
        $fixtures = __DIR__.'/fixtures/lista';
        
        //
        $extension = new CuponExtension();
        
        //
        $original = file_get_contents($fixtures.'/original.txt');
        
        //
        $this->assertEquals(
            file_get_contents($fixtures.'/esperado-ul.txt'),
            $extension->mostrarComoLista($original)
        );
        
        //
        $this->assertEquals(
            file_get_contents($fixtures.'/esperado-ol.txt'),
            $extension->mostrarComoLista($original, 'ol')
        );
    }
}