<?php

namespace App\DataFixtures;

use App\Entity\Round;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RoundFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $round = new Round();
        $tournament = $this->getReference('Tournament0');
        $round->setNumber(1);
        $round->setTournament($tournament);
        $round->setStartTimestamp(10);
        $round->setEndTimestamp(250);
        $this->addReference('Round', $round);

        $manager->persist($round);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TournamentFixtures::class,
        );
    }
}
