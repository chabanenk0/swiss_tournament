<?php

namespace App\Services;

use App\Entity\Round;
use App\Entity\Tournament;

interface PairingSystemInterface
{
    public function getCode();
    public function getName();
    public function doPairing(Tournament $tournament, Round $round, array $participants);
}
