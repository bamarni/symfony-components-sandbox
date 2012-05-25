<?php

namespace Sandbox\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Person
{
    protected $name;

    protected $gender;

    protected $nicknames;
    
    public function __construct()
    {
        $this->nicknames = new ArrayCollection();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }
    
    public function setGender($gender)
    {
        $this->gender = $gender;
        
        return $this;
    }
    
    public function getNickNames()
    {
        return $this->nicknames;
    }

    public function setNickNames($nicknames)
    {
        $this->nicknames = $nicknames;
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank);
    }
}
