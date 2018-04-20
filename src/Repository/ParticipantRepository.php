<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function getParticipantsByTournamentAndMinimalOrder($tournament, $participantOrder)
    {
        return $this->createQueryBuilder('participant')
            ->where('participant.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->andWhere('participant.participantOrder > :participantOrder')
            ->setParameter('participantOrder', $participantOrder)
            ->orderBy('participant.participantOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
