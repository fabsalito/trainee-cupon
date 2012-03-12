<?php
// src/Cupon/UsuarioBundle/Tests/Controller/DefaultControllerTest;

namespace Cupon\UsuarioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
    * @dataProvider generaUsuarios
    */
    public function testRegistro($usuario)
    {
        // crea cliente
        $client = static::createClient();

        // indica que se sigan las redirecciones
        $client->followRedirects(true);

        // raeliza un GET a la portada y guarda el DomCrawler
        $crawler = $client->request('GET', '/');

        // obtiene el enlace del botón de registro
        $enlaceRegistro = $crawler->selectLink('Regístrate')->link();

        // ejecuta el enlace del botón de registro
        $crawler = $client->click($enlaceRegistro);

        // test: verifica que se cargue la página del formulario
        $this->assertGreaterThan(
            0,
            $crawler->filter(
                'html:contains("Regístrate gratis como usuario")'
            )->count(),
            'Después de pulsar el botón Regístrate de la portada, se carga la 
            página con el formulario de registro'
        );

        // obtiene el formulario y lo llena con los datos de usuario
        $formulario = $crawler->selectButton('Registrarme')->form($usuario);

        // ejecuta el submit del formulario
        $crawler = $client->submit($formulario);

        // test: verifica que el usuario se haya creado exitosamente
        $this->assertTrue($client->getResponse()->isSuccessful());

        // test: verifica que se haya logeado al usuario
        $this->assertRegExp(
            '/(\d|[a-z])+/',
            $client->getCookieJar()->get('PHPSESSID')->getValue(),
            'La aplicación ha enviado una cookie de sesión'
        );

        // obtiene el link al perfil de usuario
        $perfil = $crawler->filter('aside section#login')->selectLink(
            'Ver mi perfil'
        )->link();

        // ejecuta el link del perfil de usuario
        $crawler = $client->click($perfil);

        // test: verifica que la direccion del mail de 
        // registro es la misma que se muestra en el perfil
        $this->assertEquals(
            $usuario['frontend_usuario[email]'],
            $crawler->filter(
                'form input[name="frontend_usuario[email]"]'
            )->attr('value'),
            'El usuario se ha registrado correctamente y sus datos se han
            guardado en la base de datos'
        );

        // ejecuta baja de usuario
        $crawler = $client->request('GET', '/es/usuario/baja');
        
        // test: verifica que se ha ejecutado correctamente la baja
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function generaUsuarios()
    {
        return array(
            array(
                array(
                'frontend_usuario[nombre]' => 'Anónimo',
                'frontend_usuario[apellidos]' => 'Apellido1 Apellido2',
                'frontend_usuario[email]' => 'anonimo'.uniqid().'@localhost.localdomain',
                'frontend_usuario[password][first]' => 'anonimo1234',
                'frontend_usuario[password][second]' => 'anonimo1234',
                'frontend_usuario[direccion]' => 'Callejuela las Operas 1010',
                'frontend_usuario[fecha_nacimiento][day]' => '01',
                'frontend_usuario[fecha_nacimiento][month]' => '01',
                'frontend_usuario[fecha_nacimiento][year]' => '1970',
                'frontend_usuario[dni]' => '11111111H',
                'frontend_usuario[numero_tarjeta]' => '123456789012345',
                'frontend_usuario[ciudad]' => '76',
                'frontend_usuario[permite_email]' => '1'
                )
            )
        );
    }
}
