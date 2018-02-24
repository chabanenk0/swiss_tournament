<?php

namespace App\DataFixtures;

use App\Entity\FirstMove;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FirstMoveFixtures extends Fixture implements DependentFixtureInterface
{
    private $firstMoveTable = ['ab4', 'ba5', 'ba3', 'cb6', 'gh4', 'fe5', 'cd4', 'bc5', 'db6', 'ac5', 'bc3', 'de5', 'ef4', 'dc5', 'gh4', 'ed6', 'hg3', 'fe5'];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count($this->firstMoveTable); $i++) {
            $firstMove = new FirstMove();
            $group = $this->getReference('FirstMovesGroup');
            $firstMove->setGroup($group);
            $firstMove->setMove($this->firstMoveTable[$i]);
            $this->addReference('FirstMove'.$i, $firstMove);

            $manager->persist($firstMove);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            FirstMovesGroupFixtures::class,
        );
    }

}
