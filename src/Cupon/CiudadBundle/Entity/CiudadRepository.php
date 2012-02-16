<?php

namespace Cupon\CiudadBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CiudadRepository extends EntityRepository
{
    public function findCercanas($ciudad_id)
    {
        // obtiene entity manager
        $em = $this->getEntityManager();
        
        // arma consulta
        $consulta = $em->createQuery('SELECT c 
                                      FROM CiudadBundle:Ciudad c
                                      WHERE c.id != :id 
                                      ORDER BY c.nombre ASC');
        
        // setea la cantidad de resultados
        $consulta->setMaxResults(5);
        
        // asigna valor a parámetro
        $consulta->setParameter('id', $ciudad_id);
        
        // retorna resultado de consulta
        return $consulta->getResult();
    }
}