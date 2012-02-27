<?php
// src/Cupon/UsuarioBundle/Form/Frontend/UsuarioType.php

namespace Cupon\UsuarioBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UsuarioType extends AbstractType
{
    // construye el formulario
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('email', 'email')
            ->add('password', 'repeated', array(
                'type' => 'password', 
                'required' => false,
                'invalid_message' => 'Las dos contraseñas deben coincidir', 
                'options' => array('label' => 'Contraseña')
            ))
            ->add('direccion')
            ->add('permite_email', 'checkbox', array('required' => false))
            ->add('fecha_nacimiento', 'birthday')
            ->add('dni')
            ->add('numero_tarjeta')
            ->add('ciudad')
            ;
    }

    // identifica al formulario mediante el nombre
    public function getName()
    {
        return 'cupon_usuariobundle_usuariotype';
    }
}