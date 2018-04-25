<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="rounds")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $tournament;

    /**
     * @ORM\Column(type="integer")
     */
    private $number = 0;

    /**
     * time of round announcement.
     * Game start and end could be different with round start and end timestamp
     * @ORM\Column(type="bigint", name="begin_timestamp")
     */
    private $startTimestamp = 0;

    /**
     * @ORM\Column(type="bigint", name="end_timestamp")
     */
    private $endTimestamp = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Tournament
     */
    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }

    /**
     * @param mixed $startTimestamp
     */
    public function setStartTimestamp($startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    /**
     * @return int
     */
    public function getEndTimestamp(): int
    {
        return $this->endTimestamp;
    }

    /**
     * @param int $endTimestamp
     */
    public function setEndTimestamp(int $endTimestamp): void
    {
        $this->endTimestamp = $endTimestamp;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Round ' . $this->getNumber();
    }
}
