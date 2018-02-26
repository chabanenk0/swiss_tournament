<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundResultRepository")
 */
class RoundResult
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="Round")
     * @ORM\JoinColumn(name="round_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $round;

    /**
     * @ORM\ManyToOne(targetEntity="Participant")
     * @ORM\JoinColumn(name="white_participant_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $whiteParticipant;


    /**
     * @ORM\ManyToOne(targetEntity="Participant")
     * @ORM\JoinColumn(name="white_participant_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $blackParticipant;

    /**
     * time of game start.
     * Game start and end could be different with round start and end timestamp
     * @ORM\Column(type="bigint", name="begin_timestamp")
     */
    private $startTimestamp = 0;

    /**
     * @ORM\Column(type="bigint", name="end_timestamp")
     */
    private $endTimestamp = 0;

    /**
     * @ORM\Column(type="bigint", name="black_time_spent_in_seconds")
     */
    private $blackTimeSpentInSeconds = 0;

    /**
     * @ORM\Column(type="bigint", name="white_time_spent_in_seconds")
     */
    private $whiteTimeSpentInSeconds = 0;

    /**
     * @ORM\ManyToOne(targetEntity="FirstMove")
     * @ORM\JoinColumn(name="first_move_id", referencedColumnName="id")
     */
    private $firstMove;

    /** results constants */
    const RESULT_BLACK_WIN = 0;
    const RESULT_DRAW = 1;
    const RESULT_WHITE_WIN = 2;

    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $result = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Round
     */
    public function getRound(): Round
    {
        return $this->round;
    }

    /**
     * @param Round $round
     */
    public function setRound(Round $round): void
    {
        $this->round = $round;
    }

    /**
     * @return Participant
     */
    public function getWhiteParticipant(): Participant
    {
        return $this->whiteParticipant;
    }

    /**
     * @param Participant $whiteParticipant
     */
    public function setWhiteParticipant(Participant $whiteParticipant): void
    {
        $this->whiteParticipant = $whiteParticipant;
    }

    /**
     * @return Participant
     */
    public function getBlackParticipant(): Participant
    {
        return $this->blackParticipant;
    }

    /**
     * @param Participant $blackParticipant
     */
    public function setBlackParticipant(Participant $blackParticipant): void
    {
        $this->blackParticipant = $blackParticipant;
    }

    /**
     * @return int
     */
    public function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }

    /**
     * @param int $startTimestamp
     */
    public function setStartTimestamp(int $startTimestamp): void
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
     * @param mixed $endTimestamp
     */
    public function setEndTimestamp(int $endTimestamp): void
    {
        $this->endTimestamp = $endTimestamp;
    }

    /**
     * @return int
     */
    public function getBlackTimeSpentInSeconds(): int
    {
        return $this->blackTimeSpentInSeconds;
    }

    /**
     * @param int $blackTimeSpentInSeconds
     */
    public function setBlackTimeSpentInSeconds(int $blackTimeSpentInSeconds): void
    {
        $this->blackTimeSpentInSeconds = $blackTimeSpentInSeconds;
    }

    /**
     * @return int
     */
    public function getWhiteTimeSpentInSeconds(): int
    {
        return $this->whiteTimeSpentInSeconds;
    }

    /**
     * @param mixed $whiteTimeSpentInSeconds
     */
    public function setWhiteTimeSpentInSeconds(int $whiteTimeSpentInSeconds): void
    {
        $this->whiteTimeSpentInSeconds = $whiteTimeSpentInSeconds;
    }

    /**
     * @return FirstMove
     */
    public function getFirstMove(): FirstMove
    {
        return $this->firstMove;
    }

    /**
     * @param FirstMove $firstMove
     */
    public function setFirstMove(FirstMove $firstMove): void
    {
        $this->firstMove = $firstMove;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param int $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }
}
