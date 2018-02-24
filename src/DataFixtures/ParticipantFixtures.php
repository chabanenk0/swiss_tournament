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
//        $player = $this->getReference('Player1');
        $participant->setParticipantOrder(1);
//        $participant->setPlayer($player);

        $manager->persist($participant);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
//            PlayerFixtures::class,
        );
    }
}