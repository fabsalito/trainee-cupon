<?php

// src/Cupon/OfertaBundle/Entitiy/OfertaRepository.php

namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OfertaRepository extends EntityRepository
{
    // ofertas recientes
    public function findRecientes($ciudad_id)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // arma consulta
        $consulta = $em->createQuery('SELECT o, t 
                                      FROM OfertaBundle:Oferta o
                                      JOIN o.tienda t 
                                      WHERE o.revisada = true 
                                      AND o.fecha_publicacion < :fecha 
                                      AND o.ciudad = :id 
                                      ORDER BY o.fecha_publicacion DESC');
                                      
        // define la cantidad de resultados
        $consulta->setMaxResults(5);
        
        // define valor para parámetros
        $consulta->setParameter('id', $ciudad_id);
        $consulta->setParameter('fecha', new \DateTime('today'));
        
        // retorna resultados
        return $consulta->getResult();
    }

    // busca la oferta del día para $ciudad
    public function findOfertaDelDia($ciudad)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // arma consulta DQL
        $dql = 'SELECT o, c, t 
                FROM OfertaBundle:Oferta o 
                JOIN o.ciudad c 
                JOIN o.tienda t 
                WHERE o.revisada = true 
                AND c.slug = :ciudad 
                AND o.fecha_publicacion < :fecha 
                ORDER BY o.fecha_publicacion DESC';

        // crea consulta
        $consulta = $em->createQuery($dql);
        
        // setea parámetros
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('fecha', new \DateTime('now'));
        
        // setea el número de resultados
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
    
    // busca oferta especificada por $ciudad y $slug
    public function findOferta($ciudad, $slug)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // define consulta
        $consulta = $em->createQuery('SELECT o, c, t 
                                      FROM OfertaBundle:Oferta o 
                                      JOIN o.ciudad c 
                                      JOIN o.tienda t 
                                      WHERE o.revisada = true 
                                      AND o.slug = :slug
                                      AND c.slug = :ciudad');
        
        // define parámetros
        $consulta->setParameter('slug', $slug);
        $consulta->setParameter('ciudad', $ciudad);
        
        // realiza consulta
        $consulta->setMaxResults(1);
        
        // devuelve oferta
        return $consulta->getSingleResult();
    }
    
    // busca las ofertas relacionadas a las ciudad
    public function findRelacionadas($ciudad)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // define consulta
        $consulta = $em->createQuery('SELECT o, c 
                                     FROM OfertaBundle:Oferta o
                                     JOIN o.ciudad c 
                                     WHERE o.revisada = true 
                                     AND o.fecha_publicacion <= :fecha 
                                     AND c.slug != :ciudad 
                                     ORDER BY o.fecha_publicacion DESC');
        
        // define parámetros
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('fecha', new \DateTime('today'));
        
        // realiza consulta
        $consulta->setMaxResults(5);
        
        // devuelve oferta
        return $consulta->getResult();
    }
}