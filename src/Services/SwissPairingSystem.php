<?php

namespace App\Services;

<<<<<<< HEAD

=======
>>>>>>> remotes/origin/feature-pairing-services
use App\Entity\Round;
use App\Entity\Tournament;

class SwissPairingSystem implements PairingSystemInterface
{
<<<<<<< HEAD

=======
>>>>>>> remotes/origin/feature-pairing-services
    const CODE = 2;
    public function getCode()
    {
        return self::CODE;
    }

    public function getName()
    {
        return 'Swiss system';
    }

    public function doPairing(Tournament $tournament, Round $round, array $participants)
    {
        return [];
    }
}
