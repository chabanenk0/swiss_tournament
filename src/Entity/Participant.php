<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="participants", cascade="remove")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private $player;

    /**
     * @ORM\Column(type="integer", name="participant_order")
     */
    private $particpantOrder;

    /**
     * @ORM\OneToMany(targetEntity="Tournament", mappedBy="participant", cascade="remove")
          */
    private $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return mixed
     */
    public function getParticpantOrder()
    {
        return $this->particpantOrder;
    }

    /**
     * @param mixed $particpantOrder
     */
    public function setParticpantOrder($particpantOrder): void
    {
        $this->particpantOrder = $particpantOrder;
    }


    /**
     * @param Player $player
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return ArrayCollection
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * @param Tournament $tournament
     */
    public function addTorunament(Tournament $tournament)
    {
        $this->tournaments->add($tournament);
    }

    /**
     * @param Tournament $tournament
     */
    public function removeTournament(Tournament $tournament)
    {
        $this->tournaments->removeElement($tournament);
    }
}
