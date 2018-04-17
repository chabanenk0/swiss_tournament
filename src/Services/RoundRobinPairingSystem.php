<?php

namespace App\Services;

<<<<<<< HEAD

=======
>>>>>>> remotes/origin/feature-pairing-services
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

    public function doPairing(Tournament $tournament, Round $round, array $participants)
    {
        $pairs = array();

        foreach ($participants as $blackParticipant) {
<<<<<<< HEAD
            foreach($participants as $whiteParticipant) {
=======
            foreach ($participants as $whiteParticipant) {
>>>>>>> remotes/origin/feature-pairing-services
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
