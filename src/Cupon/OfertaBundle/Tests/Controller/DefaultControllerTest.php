<?php
// src/Cupon/OfertaBundle/Tests/Controller/DefaultControllerTest.php

namespace Cupon\OfertaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /** @test */
    public function laPortadaSimpleRedirigeAUnaCiudad()
    {
        // crea un cliente
        $client = static::createClient();
        
        // realiza un GET sobre / del cliente
        $client->request('GET', '/');
        
        // test: verifica que se redirija a la portada de una ciudad
        $this->assertEquals(302, $client->getResponse()->getStatusCode(),
            'La portada redirige a la portada de una ciudad (status 302)'
        );
    }

    /** @test */
    public function laPortadaSoloMuestraUnaOfertaActiva()
    {
        // crea cliente
        $client = static::createClient();

        // indica que se sigan las redirecciones
        $client->followRedirects(true);

        // realiza un GET sobre la portada (/) y obtiene objeto DomClawer
        $crawler = $client->request('GET', '/');

        // obtiene la cantidad de nodos que contengan Comprar        
        $ofertasActivas = $crawler->filter(
            'article section.descripcion a.boton:contains("Comprar")'
        )->count();
        
        // test: verifica que solo haya una oferta activa en la portada
        $this->assertEquals(1, $ofertasActivas,
            'La portada muestra una única oferta activa que se puede comprar'
        );
    }

    /** @test */
    public function losUsuariosPuedenRegistrarseDesdeLaPortada()
    {
        // crea cliente
        $client = static::createClient();

        // realiza un GET sobre la portada (/)
        $client->request('GET', '/');

        // sigue la redirección de la portada y obtiene l objeto DomClawler
        $crawler = $client->followRedirect();

        // obtiene la cantidad de nodos que contengan el texto 'Regístrate ya'
        $numeroEnlacesRegistrarse = $crawler->filter(
            'html:contains("Regístrate")'
        )->count();
        
        // test: verifica que exista al menos un nodo con el texto seleccionado
        $this->assertGreaterThan(0, $numeroEnlacesRegistrarse,
            'La portada muestra al menos un enlace o botón para registrarse'
        );
    }

    /** @test */
    public function losUsuariosAnonimosVenLaCiudadPorDefecto()
    {
        // crea cliente
        $client = static::createClient();

        // indica seguimiento de redirecciones
        $client->followRedirects(true);

        // realiza un GET sobre la portada y almacena el objeto DOM
        $crawler = $client->request('GET', '/');

        // obtiene la ciudad por defecto
        $ciudadPorDefecto = $client->getContainer()->getParameter(
            'cupon.ciudad_por_defecto'
        );

        // obtiene la ciudad de la portada
        $ciudadPortada = $crawler->filter(
            'header nav select option[selected="selected"]'
        )->attr('value');

        // test: la ciudad de la portada debe igual a la ciudad por defecto
        $this->assertEquals($ciudadPorDefecto, $ciudadPortada,
            'Los usuarios anónimos ven seleccionada la ciudad por defecto'
        );
    }

    /** @test */
    //public function losUsuariosAnonimosDebenLoguearseParaPoderComprar()
    //{
        // crea cliente
    //    $client = static::createClient();

        // realiza un GET sobre la portada del cliente
    //    $client->request('GET', '/');

        // sigue redirección de la portada
    //    $crawler = $client->followRedirect();

        // obtiene el nodo para comprar y lo define como link
    //    $comprar = $crawler->selectLink('Comprar')->link();

        // simula click en comprar
    //    $client->click($comprar);

        // sigue redirección
    //    $crawler = $client->followRedirect();

        // test: verifica que la redirección haya sido al formulario de login
    //    $this->assertRegExp(
    //        '/.*\/usuario\/login_check/',
    //        $crawler->filter('article form')->attr('action'),
    //        'Después de pulsar el botón de comprar, el usuario anónimo ve el formulario de login'
    //    );
    //}
    // ...
}
