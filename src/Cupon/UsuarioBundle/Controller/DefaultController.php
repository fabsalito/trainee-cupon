<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\UsuarioBundle\Form\Frontend\UsuarioType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller
{
    // página con perfil de usuario
    public function perfilAction()
    {
        // obtiene el usuario logeado
        $usuario = $this->get('security.context')->getToken()->getUser();
        
        // crea formulario
        $formulario = $this->createForm(new UsuarioType(), $usuario);
        
        // obtiene petición
        $peticion = $this->getRequest();

        // verifica si debe actualizar los datos
        if ($peticion->getMethod() == 'POST') {
            // obtiene password original
            $passwordOriginal = $formulario->getData()->getPassword();
            
            // vuelca los datos de la petición en el formulario
            $formulario->bindRequest($peticion);
            
            // valida formulario
            if ($formulario->isValid()) {
                // verifica si el usuario ha dejado la contraseña vacía (no quiere hacer cambio)
                if (null == $usuario->getPassword()) {
                    // setea el password original
                    $usuario->setPassword($passwordOriginal);
                }
                else {
                    // obtiene encoder de usuario
                    $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                
                    // codifica password
                    $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
                    
                    // almacena password
                    $usuario->setPassword($passwordCodificado);
                }
                    
                // obtiene entity manager
                $em = $this->getDoctrine()->getEntityManager();
                        
                // persiste los datos del usuario
                $em->persist($usuario);
                        
                // almacena datos de usuario en base de datos
                $em->flush();
                        
                // crea mensaje flash
                $this->get('session')->setFlash('info',
                    'Los datos de tu perfil se han actualizado correctamente'
                );
                        
                // retorna respuesta
                return $this->redirect($this->generateUrl('usuario_perfil'));
            }
        }

        // retorna respuesta
        return $this->render('UsuarioBundle:Default:perfil.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
    }
    
    // renderiza formulario de registro
    public function registroAction()
    {
        // obtiene petición
        $peticion = $this->getRequest();
        
        // crea nuevo objeto usuario
        $usuario = new Usuario();

        // crea formulario
        $formulario = $this->createForm(new UsuarioType(), $usuario);
        
        // valida y procesa datos de formulario si es requerido
        if ($peticion->getMethod() == 'POST') {
            // obtiene los datos de la petición y los asocia al formulario
            $formulario->bindRequest($peticion);
            
            if ($formulario->isValid()) {
                // obtiene enconder
                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);

                // setea salt para el usuario
                $usuario->setSalt(md5(time()));

                // define password con codificación
                $passwordCodificado = $encoder->encodePassword(
                    $usuario->getPassword(),
                    $usuario->getSalt()
                );

                // setea password para el usuario
                $usuario->setPassword($passwordCodificado);

                // obtiene el entity manager
                $em = $this->getDoctrine()->getEntityManager();

                // persiste los datos para el usuario
                $em->persist($usuario);

                // almacena los datos en base de datos
                $em->flush();
                
                // define mensaje flash a mostrar al usuario
                $this->get('session')->setFlash('info','!Enhorabuena!, Te has registrado correctamente en Cupon');
                
                // define token
                $token = new UsernamePasswordToken(
                    $usuario,
                    $usuario->getPassword(),
                    'usuarios',
                    $usuario->getRoles()
                );

                // setea token
                $this->container->get('security.context')->setToken($token);

                // redirecciona a la portada
                return $this->redirect($this->generateUrl('portada', array('ciudad' => 'madrid')));
            }
        }

        // retorna respuesta
        return $this->render('UsuarioBundle:Default:registro.html.twig',
            array('formulario' => $formulario->createView())
        );
    }

    // compras del usuario
    public function comprasAction()
    {
        // obtiene ID del usuario logeado
        $usuario_id = $this->get('security.context')->getToken()->getUser()->getId();

        // obtiene entity manager
        $em = $this->getDoctrine()->getEntityManager();

        // obtiene las últimas compras del usuario logeado
        $compras = $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario_id);

        // retorna respuesta
        return $this->render('UsuarioBundle:Default:compras.html.twig', array(
            'compras' => $compras
        ));
    }
    
    // formulario login
    public function loginAction()
    {
        // obtiene la petición
        $peticion = $this->getRequest();
        
        // obtiene sesión
        $sesion = $peticion->getSession();
        
        // obtiene el error
        $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
    
        // retorna respuesta
        return $this->render('UsuarioBundle:Default:login.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        ));
    }
    
    // (mini) formulario login
    public function cajaLoginAction()
    {
        // obtiene la petición
        $peticion = $this->getRequest();
        
        // obtiene sesión
        $sesion = $peticion->getSession();
        
        // obtiene el error
        $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        
        // obtiene usuario
        $usuario = $this->get('security.context')->getToken()->getUser();
    
        // retorna respuesta
        return $this->render('UsuarioBundle:Default:cajaLogin.html.twig', array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
            'usuario' => $usuario
        ));
    }
}
