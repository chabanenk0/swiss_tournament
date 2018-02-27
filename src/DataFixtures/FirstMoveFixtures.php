<?php

namespace App\DataFixtures;

use App\Entity\FirstMove;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FirstMoveFixtures extends Fixture implements DependentFixtureInterface
{
    private $firstMoveTable = [
        'ab4 ba5 ba3 cb6 gh4 fe5',
        'ab4 ba5 ed4 ab6 fe3 ba7',
        'ab4 ba5 ed4 ab6 fe3 dc5',
        'ab4 ba5 ed4 ab6 fe3 fe5',
        'ab4 ba5 ed4 ab6 fe3 hg5',
        'ab4 ba5 gh4 fg5 hf6 eg5',
        'ab4 bc5 ba5 cb4',
        'ab4 bc5 ba5 cb4 ed4 fe5',
        'ab4 bc5 ba5 cb4 ed4 fg5',
        'ab4 bc5 ba5 cb4 ed4 hg5',
        'ab4 de5 ba3 fg5',
        'ab4 de5 ba3 fg5 gf4',
        'ab4 de5 ed4 hg5 ba5',
        'ab4 de5 ed4 hg5 bc5',
        'ab4 de5 gf4 eg3',
        'ab4 fg5 ba3 ef6 ed4',
        'cb4 ba5 dc3 fe5 gh4 cb6',
        'cb4 ba5 ef4 ac3 db4',
        'cb4 bc5 dc3 fe5',
        'cb4 bc5 dc3 fe5 ed2 ef6',
        'cb4 bc5 dc3 fe5 ef4 ef6',
        'cb4 bc5 dc3 fe5 gh4 ab6',
        'cb4 bc5 ed4 ce3 fd4 cb6',
        'cb4 bc5 ed4 ce3 fd4 de5',
        'cb4 de5 dc3 fg5',
        'cb4 fg5 ba5 ef6 ed4',
        'cb4 fg5 ed4 ef6',
        'cb4 fg5 ed4 ef6 de5 fd4',
        'cb4 fg5 ed4 ef6 gh4',
        'cb4 hg5 ba5 gh4 ed4',
        'cd4 ba5 ef4 fg5 fe3 ab4',
        ];

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

