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
    private $id;

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
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="white_player_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $whitePlayer;


    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="white_player_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $blackPlayer;

    /**
     * time of game start.
     * Game start and end could be different with round start and end timestamp
     * @ORM\Column(type="bigint", name="begin_timestamp")
     */
    private $startTimestamp;

    /**
     * @ORM\Column(type="bigint", name="end_timestamp")
     */
    private $endTimestamp;

    /**
     * @ORM\Column(type="bigint", name="black_time_spent_in_seconds")
     */
    private $blackTimeSpentInSeconds;

    /**
     * @ORM\Column(type="bigint", name="white_time_spent_in_seconds")
     */
    private $whiteTimeSpentInSeconds;

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
     * @return Player
     */
    public function getWhitePlayer(): Player
    {
        return $this->whitePlayer;
    }

    /**
     * @param Player $whitePlayer
     */
    public function setWhitePlayer(Player $whitePlayer): void
    {
        $this->whitePlayer = $whitePlayer;
    }

    /**
     * @return Player
     */
    public function getBlackPlayer(): Player
    {
        return $this->blackPlayer;
    }

    /**
     * @param Player $blackPlayer
     */
    public function setBlackPlayer($blackPlayer): void
    {
        $this->blackPlayer = $blackPlayer;
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
}
