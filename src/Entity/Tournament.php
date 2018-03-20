<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title = '';

    /**
     * @ORM\Column(type="text")
     */
    private $description = '';

    /**
     * @ORM\Column(type="bigint", name="begin_timestamp")
     */
    private $startTimestamp = 0;

    /**
     * @ORM\Column(type="bigint", name="end_timestamp")
     */
    private $endTimestamp = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place = '';

    /**
     * @ORM\Column(type="float", name="place_gps_x")
     */
    private $placeGpsX = 0;

    /**
     * @ORM\Column(type="float", name="place_gps_y")
     */
    private $placeGpsY = 0;

    /** Pairing system  constants */
    const PAIRING_SYSTEM_SWISS = 1;
    const PAIRING_SYSTEM_ROUND = 2;

    /**
     * @ORM\Column(type="integer", name="pairing_system")
     */
    private $pairingSystem = 0;

    /**
     * @ORM\Column(type="integer", name="number_of_rounds")
     */
    private $numberOfRounds = 0;

    /** Tournament status constants */
    const STATUS_PLANNED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    const STATUS_SLUGS = [
      'planned'=> self::STATUS_PLANNED,
      'in_progress'=> self::STATUS_IN_PROGRESS,
      'completed'=> self::STATUS_COMPLETED,
    ];

    /**
     * @ORM\Column(type="integer", name="status")
     */
    private $status = 0;

    /**
     * @ORM\OneToMany(targetEntity="Round", mappedBy="tournament", cascade="remove")
     */
    private $rounds;

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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStartTimestamp()
    {
        return  $this->startTimestamp;
    }

    /**
     * @param mixed $startTimestamp
     */
    public function setStartTimestamp($startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    /**
     * @return mixed
     */
    public function getEndTimestamp()
    {
        return $this->endTimestamp;
    }

    /**
     * @param mixed $endTimestamp
     */
    public function setEndTimestamp($endTimestamp): void
    {
        $this->endTimestamp = $endTimestamp;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place): void
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getPlaceGpsX()
    {
        return $this->placeGpsX;
    }

    /**
     * @param mixed $placeGpsX
     */
    public function setPlaceGpsX($placeGpsX): void
    {
        $this->placeGpsX = $placeGpsX;
    }

    /**
     * @return mixed
     */
    public function getPlaceGpsY()
    {
        return $this->placeGpsY;
    }

    /**
     * @param mixed $placeGpsY
     */
    public function setPlaceGpsY($placeGpsY): void
    {
        $this->placeGpsY = $placeGpsY;
    }

    /**
     * @return mixed
     */
    public function getPairingSystem()
    {
        return $this->pairingSystem;
    }

    /**
     * @param mixed $pairingSystem
     */
    public function setPairingSystem($pairingSystem): void
    {
        $this->pairingSystem = $pairingSystem;
    }

    /**
     * @return mixed
     */
    public function getNumberOfRounds()
    {
        return $this->numberOfRounds;
    }

    /**
     * @param mixed $numberOfRounds
     */
    public function setNumberOfRounds($numberOfRounds): void
    {
        $this->numberOfRounds = $numberOfRounds;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
