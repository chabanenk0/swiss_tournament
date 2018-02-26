<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $participant = new Participant();
        $player = $this->getReference('Player1');
        $tournament = $this->getReference('Tournament0');
        $participant->setParticipantOrder(1);
        $participant->setPlayer($player);
        $participant->setTournament($tournament);



        $this->addReference('Participant1', $participant);
        $manager->persist($participant);

//        $participant2 = new Participant();
//        $player2 = $this->getReference('Player2');
//        $participant2->setParticipantOrder(1);
//        $participant->setPlayer($player2);
//        $this->addReference('Participant2', $participant2);
//        $manager->persist($participant2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PlayerFixtures::class,
            TournamentFixtures::class,

        );
    }
}