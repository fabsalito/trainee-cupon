<?php

// src/Cupon/OfertaBundle/Tests/Entity/OfertaTest.php

namespace Cupon\OfertaBundle\Tests\Entity;

use Symfony\Component\Validator\ValidatorFactory;
use Cupon\OfertaBundle\Entity\Oferta;

class OfertaTest extends \PHPUnit_Framework_TestCase
{
    // validador de sf2
    private $validator;
    
    // método inicial para phpunit
    protected function setUp() {
        // obtiene el validador de sf2
        $this->validator = ValidatorFactory::buildDefault()->getValidator();
    }
    
    // validar la entidad Oferta
    private function validar(Oferta $oferta){
        // valida y almacena errores para la validación entidad Oferta
        $errores = $this->validator->validate($oferta);

        // almacena error
        $error = $errores[0];

        // retorna errores
        return array($errores, $error);
    }

    // validación de slug
    public function testValidarSlug() {
        // define una instancia de la entidad Oferta
        $oferta = new Oferta();

        // define la propiedad 'nombre' para la entidad
        $oferta->setNombre('Oferta de prueba');

        // obtiene la propiedad 'slug' de la entidad
        $slug = $oferta->getSlug();
        
        // test: el slug debe ser igual a 'oferta-de-prueba'
        $this->assertEquals('oferta-de-prueba', $slug,
            'El slug se asigna automáticamente a partir del nombre'
        );
    }

    // validación de descripción
    public function testValidarDescripcion() {
        // define una instancia de la entidad Oferta
        $oferta = new Oferta();

        // define la propiedad 'nombre' para la entidad
        $oferta->setNombre('Oferta de prueba');

        // valida y almacena errores para la validación de la entidad Oferta
        $errores = $this->validator->validate($oferta);

        // test: la propiedad 'descripción' no puede dejarse en blanco
        $this->assertGreaterThan(0, count($errores),
            'La descripción no puede dejarse en blanco'
        );

        // almacena primer error
        $error = $errores[0];

        // test: verifica que el texto del error sea 'This value should not be blank'
        $this->assertEquals(
            'This value should not be blank',
            $error->getMessageTemplate()
        );

        // test: verifica que el origen del error de validación sea la propiedad 'descripcion'
        $this->assertEquals('descripcion', $error->getPropertyPath());

        // define la propiedad 'descripción'
        $oferta->setDescripcion('Descripción de prueba');

        // valida y almacena errores
        $errores = $this->validator->validate($oferta);

        // test: verifica que la descripción no pueda tener menos de 30 caracteres
        $this->assertGreaterThan(0, count($errores),
            'La descripción debe tener al menos 30 caracteres'
        );

        // obtiene primer error
        $error = $errores[0];

        // test: verifica que el mensaje contenga 'This value is too short'
        $this->assertRegExp(
            "/This value is too short/",
            $error->getMessageTemplate()
        );

        // test: verifica que el origen del error de validación sea la propiedad 'descripcion'
        $this->assertEquals('descripcion', $error->getPropertyPath());
    }

    // validación de fechas
    public function testValidarFechas() {
        // define una instancia de la entidad Oferta
        $oferta = new Oferta();

        // define la propiedad 'nombre' para la entidad
        $oferta->setNombre('Oferta de prueba');

        // define la propiedad 'descripcion' de la entidad para evitar error de validación aquí
        $oferta->setDescripcion('Descripción de prueba larga para que tenga 
            más de 30 caracteres y no se produzca un error de validación'
        );

        // define la propiedad 'fechaPublicacion'
        $oferta->setFechaPublicacion(new \DateTime('today'));

        // define la propiedad 'fechaExpiracion'
        $oferta->setFechaExpiracion(new \DateTime('yesterday'));

        // valida y almacena errores
        $errores = $this->validator->validate($oferta);

        // test: verifica que la fecha de expiración no sea menor a la fecha de publicación
        $this->assertGreaterThan(0, count($errores),
            'La oferta debe expirar después de ser publicada'
        );

        // obtiene el primer error
        $error = $errores[0];

        // test: verifica que el mensaje sea
        // 'La fecha de expiración debe ser posterior a la fecha de publicación' 
        $this->assertEquals('La fecha de expiración debe ser posterior a la fecha de publicación', 
            $error->getMessageTemplate()
        );

        // test: verifica que el origen del error de validación sea el campo 'fechaValida'
        $this->assertEquals('fechaValida', $error->getPropertyPath());
    }

    // validación de precio
    public function testValidarPrecio(){
        // define instancia de la entidad Oferta
        $oferta = new Oferta();

        // define la propiedad 'nombre' para la entidad
        $oferta->setNombre('Oferta de prueba');

        // define la propiedad 'descripción' para la entidad con el objetivo
        // de evitar errores de validación con esta
        $oferta->setDescripcion('Descripción de prueba larga para que tenga
            más de 30 caracteres y no se produzca un error de validación'
        );

        // define la propiedad 'fechaPublicacion' para la entidad con el objetivo
        // de evitar errores de validación con esta
        $oferta->setFechaPublicacion(new \DateTime('today'));

        // define la propiedad 'fechaExpiracion' para la entidad con el objetivo
        // de evitar errores de validación con esta
        $oferta->setFechaExpiracion(new \DateTime('tomorrow'));

        // define umbral
        $oferta->setUmbral(3);

        // define precio para producir error de validación
        $oferta->setPrecio(-10);

        // valida y almacena errores
        $errores = $this->validator->validate($oferta);

        // test: verifica que el precio no pueda ser un número negativo            
        $this->assertGreaterThan(0, count($errores),
            'El precio no puede ser un número negativo'
        );

        // obtiene el primer error
        $error = $errores[0];

        // test: verifica que el mensaje contenga 'This value should be .* or more'
        $this->assertRegExp(
            "/This value should be .* or more/",
            $error->getMessageTemplate()
        );

        // test: verifica que el origen del error sea la propiedad 'precio'
        $this->assertEquals('precio', $error->getPropertyPath());
    }
    // ...
}
