<?php

// src/Cupon/OfertaBundle/Entitiy/OfertaRepository.php

namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OfertaRepository extends EntityRepository
{
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
        
        // setea par�metros
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('fecha', new \DateTime('now'));
        
        // setea el n�mero de resultados
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
}