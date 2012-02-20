<?php

// src/Cupon/UsuarioBundle/Entitiy/UsuarioRepository.php

namespace Cupon\UsuarioBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository
{
    // obtiene todas las compras del usuario $usuario_id
    public function findTodasLasCompras($usuario)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();

        // arma consulta
        $consulta = $em->createQuery('SELECT v, o, t 
                                      FROM OfertaBundle:Venta v
                                      JOIN v.oferta o 
                                      JOIN o.tienda t 
                                      WHERE v.usuario = :id 
                                      ORDER BY v.fecha DESC');

        // ejecuta consulta
        $consulta->setParameter('id', $usuario);
        
        // retorna resultados
        return $consulta->getResult();
    }
}