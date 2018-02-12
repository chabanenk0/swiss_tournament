<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * this table contains first moves for random generation.
 * It is possible to use different library (group_ of first moves and select them.
 * @ORM\Entity(repositoryClass="App\Repository\FirstMoveRepository")
 */
class FirstMove
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Text description of the move (e2-e4 etc)
     * @ORM\Column(type="string", length=255)
     */
    private $move;

    /**
     * @ORM\ManyToOne(targetEntity="FirstMovesGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMove(): string
    {
        return $this->move;
    }

    /**
     * @param string $move
     */
    public function setMove($move): void
    {
        $this->move = $move;
    }

    /**
     * @return FirstMovesGroup
     */
    public function getGroup(): FirstMovesGroup
    {
        return $this->group;
    }

    /**
     * @param FirstMovesGroup $group
     */
    public function setGroup(FirstMovesGroup $group): void
    {
        $this->group = $group;
    }
}
