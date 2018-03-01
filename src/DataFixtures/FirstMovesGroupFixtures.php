<?php

namespace App\DataFixtures;

use App\Entity\FirstMovesGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FirstMovesGroupFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $firstMovesGroup = new FirstMovesGroup();
        $firstMovesGroup->setName('draughts-64');
        $this->addReference('FirstMovesGroup', $firstMovesGroup);
        $manager->persist($firstMovesGroup);
        $manager->flush();
    }
}
