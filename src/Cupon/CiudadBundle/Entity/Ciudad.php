<?php

namespace Cupon\CiudadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cupon\OfertaBundle\Util\Util;

/**
* @ORM\Entity
*/
class Ciudad
{
    
    /** 
    * @ORM\Id
    * @ORM\Column(type="integer") 
    * @ORM\GeneratedValue
    */
    protected $id;
    /** @ORM\Column(type="string", length=100) */
    protected $nombre;
    /** @ORM\Column(type="string", length=100) */
    protected $slug;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        // setea el nombre seguro
        $this->slug = Util::getSlug($nombre);
    }
    
    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function __toString()
    {
        return $this->getNombre();
    }
}