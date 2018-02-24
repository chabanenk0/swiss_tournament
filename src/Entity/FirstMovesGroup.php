<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FirstMovesGroupRepository")
 */
class FirstMovesGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Group name
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
