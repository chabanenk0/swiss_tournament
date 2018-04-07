<?php

namespace App\DataFixtures;

use App\Entity\Tournament;
use App\Services\RoundRobinPairingSystem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TournamentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $tournament = new Tournament();

            $tournament->setStatus(Tournament::STATUS_PLANNED);
            $tournament->setTitle('Title'.$i);
            $tournament->setDescription('Description'.$i);
            $tournament->setEndTimestamp(100+$i);
            $tournament->setNumberOfRounds($i);
            $tournament->setPairingSystem(2);
            $tournament->setPlace('Place'.$i);
            $tournament->setPlaceGpsX(23.01);
            $tournament->setPlaceGpsY(145.7);
            $tournament->setStartTimestamp($i);
            $tournament->setPairingSystem(RoundRobinPairingSystem::CODE);

            $manager->persist($tournament);

            $this->addReference('Tournament'.$i, $tournament);
        }

        $manager->flush();
    }
}
