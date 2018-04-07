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
        for ($i = 0; $i < 2; $i++) {
            $participant = new Participant();
            $player = $this->getReference('Player'.$i);
            $tournament = $this->getReference('Tournament'.$i);
            $participant->setParticipantOrder(1);
            $participant->setPlayer($player);
            $participant->setTournament($tournament);

            $this->addReference('Participant'.$i, $participant);
            $manager->persist($participant);
        }

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
