<?php

namespace Cupon\TiendaBundle\Form\Extranet;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TiendaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nombre')
            //->add('slug')
            ->add('login', 'text', array('read_only' => true))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir',
                'options' => array('label' => 'Contraseña'),
                'required' => false
            ))
//            ->add('salt')
            ->add('descripcion')
            ->add('direccion')
            ->add('ciudad')
        ;
    }

    public function getName()
    {
        return 'cupon_tiendabundle_tiendatype';
    }
}

