<?php

namespace App\Services;

use App\Entity\Round;
use App\Entity\Tournament;

class SwissPairingSystem implements PairingSystemInterface
{
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
