<?php

namespace App\Repository;

use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RoundRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Round::class);
    }

    public function getRoundsWithResultsByTournament($tournament)
    {
        return $this->createQueryBuilder('round')
            ->where('round.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->orderBy('round.number', 'ASC')
            ->join('round.results')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('r')
            ->where('r.something = :value')->setParameter('value', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
