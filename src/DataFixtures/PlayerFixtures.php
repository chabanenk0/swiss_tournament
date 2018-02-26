<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $date = new \DateTime('now');
        for ($i = 0; $i < 9; $i++) {
            $player = new Player();
            $player->setFirstName('Gamer'.$i);
            $player->setLastName('Surname'.$i);
            $player->setFathersName('Fatherson'.$i);
            $player->setBirthDate($date);
            $player->setPhone('093117788'.$i);
            $player->setGender(rand(1, 2));
            $player->setFederation('Federation'.$i);
            $player->setAvatarSrc('image');
            $player->setEmail('player'.$i.'@gmail.com');
            $player->setCity('Town'.$i);
            $player->setRange(5);
            //$player->addParticipant();

            $this->addReference('Player'.$i, $player);

            $manager->persist($player);
        }

        $manager->flush();
    }

}
