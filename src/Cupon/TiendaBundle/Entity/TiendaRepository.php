<?php

// src/Cupon/OfertaBundle/Entitiy/TiendaRepository.php

namespace Cupon\TiendaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TiendaRepository extends EntityRepository
{
    // ultimas ofertas publicadas por la tienda
    public function findUltimasOfertasPublicadas($tienda_id, $limite = 10)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // arma consulta
        $consulta = $em->createQuery('SELECT o, t 
                                      FROM OfertaBundle:Oferta o 
                                      JOIN o.tienda t 
                                      WHERE o.revisada = true 
                                      AND o.fecha_publicacion < :fecha 
                                      AND o.tienda = :id 
                                      ORDER BY o.fecha_expiracion DESC');
                                      
        // define la cantidad de resutados
        $consulta->setMaxResults($limite);
        
        // define valor para parámetros
        $consulta->setParameter('id', $tienda_id);
        $consulta->setParameter('fecha', new \DateTime('now'));
        
        // retorna resultados
        return $consulta->getResult();
    }
    
    // tiendas cercanas
    public function findCercanas($tienda, $ciudad)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // arma consulta
        $consulta = $em->createQuery('SELECT t, c 
                                      FROM TiendaBundle:Tienda t
                                      JOIN t.ciudad c 
                                      WHERE c.slug = :ciudad 
                                      AND t.slug != :tienda');

        // define cantidad de resultados
        $consulta->setMaxResults(5);
        
        // define valor para parámetros
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('tienda', $tienda);
        
        // retorna resultados
        return $consulta->getResult();
    }
}