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
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="participants")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private $player;

    /**
     * @ORM\Column(type="integer", name="participant_order")
     */
    private $participantOrder = 0;

    /**
     * @todo store in db or retrieve before round generation
     * @var float
     */
    private $points;

    /**
     * participant's rank in the tournament.
     * Calculate before generation or store to db
     * @var int
     */
    private $rank;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private $tournament;

    /**
     * Map with doctrine ? how to match, if different fields are matching (white and black player)
     * Better to query before and set the received values
     * @var ArrayCollection
     */
    private $roundResults;

    public function __construct()
    {
        $this->roundResults = new ArrayCollection();
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
        return $this->participantOrder;
    }

    /**
     * @param mixed $particpantOrder
     */
    public function setParticipantOrder($participantOrder): void
    {
        $this->participantOrder = $participantOrder;
    }


    /**
     * @param Player $player
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @return float
     */
    public function getPoints(): float
    {
        return $this->points;
    }

    /**
     * @param float $points
     */
    public function setPoints(float $points): void
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     */
    public function setRank(int $rank): void
    {
        $this->rank = $rank;
    }


    public function addRoundResult(RoundResult $roundResult): void
    {
        $this->roundResults->add($roundResult);
    }


    public function removeRoundResult(RoundResult $roundResult): void
    {
        $this->roundResults->removeElement($roundResult);
    }

    /**
     * @return ArrayCollection
     */
    public function getRoundResults()
    {
        return $this->roundResults;
    }

    public function __toString()
    {
        return $this->tournament ? $this->tournament->getTitle() : 'undefined torunament participant';
    }
}
