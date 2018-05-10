<?php

namespace App\Services;

use App\Repository\ParticipantRepository;

class SwissTournamentManager
{
    private $participantRepository;

    public function __construct(ParticipantRepository $participantRepository)
    {
        $this->participantRepository = $participantRepository;
    }

    public function getParticipantsDataByTournamentId($tournamentId)
    {
        $participants = $this->participantRepository->findBy(['tournament' => $tournamentId]);

        return $participants;
    }

    public function arrangeRoundResultsByRound(array $roundResults)
    {
        $roundResultsByRound = array();

        foreach ($roundResults as $roundResult) {
            $roundNumber = $roundResult->getRoundId();
            if (!array_key_exists($roundNumber, $roundResultsByRound)) {
                $roundResultsByRound[$roundNumber] = [];
            }

            $roundResultsByRound[$roundNumber][] = $roundResult;
        }
    }
}
