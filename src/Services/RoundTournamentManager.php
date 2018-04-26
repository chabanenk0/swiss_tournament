<?php

namespace App\Services;

use App\Entity\RoundResult;
use Doctrine\ORM\EntityManagerInterface;

class RoundTournamentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getRoundResultsForTournament($tournament)
    {
        return $this->entityManager->getRepository(RoundResult::class)
            ->findBy(['tournament' => $tournament]);
    }

    public function getRoundResultByParticipants($whiteParticipant, $blackParticipant)
    {
        return $this->entityManager->getRepository(RoundResult::class)
            ->findOneBy(['whiteParticipant' => $whiteParticipant, 'blackParticipant' => $blackParticipant]);
    }
}
