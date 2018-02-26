<?php

namespace App\DataFixtures;

use App\Entity\RoundResult;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RoundResultFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
//        $roundResult = new RoundResult();
//        $roundResult->setTournament($this->getReference('Tournament1'));
//        $roundResult->setStartTimestamp(0);
//        $roundResult->setEndTimestamp(100);
//        $roundResult->setBlackParticipant($this->getReference('Participant1'));
//        $roundResult->setBlackTimeSpentInSeconds(20);
//        $roundResult->setWhiteParticipant($this->getReference('Participant2'));
//        $roundResult->setWhiteTimeSpentInSeconds(25);
//        $roundResult->setFirstMove($this->getReference('FirstMove1'));
//        $roundResult->setRound($this->getReference('Round'));
//
//        $manager->persist($roundResult);
//        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TournamentFixtures::class,
            ParticipantFixtures::class,
        );
    }
}
