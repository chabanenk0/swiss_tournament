<?php

namespace App\Services;

use App\Entity\Round;
use App\Entity\RoundResult;
use App\Entity\Tournament;

class RoundRobinPairingSystem implements PairingSystemInterface
{
    const CODE = 1;
    public function getCode()
    {
        return self::CODE;
    }

    public function getName()
    {
        return 'Round robin';
    }

    public function doPairing(Tournament $tournament, Round $round, array $participants, array $roundResults)
    {
        $pairs = array();

        foreach ($participants as $blackParticipant) {
            foreach ($participants as $whiteParticipant) {
                if ($blackParticipant->getId() === $whiteParticipant->getId()) {
                    continue;
                }

                $pair = new RoundResult();
                $pair->setTournament($tournament);
                $pair->setRound($round);
                $pair->setWhiteParticipant($whiteParticipant);
                $pair->setBlackParticipant($blackParticipant);

                $pairs[] = $pair;
            }
        }

        return $pairs;
    }
}
