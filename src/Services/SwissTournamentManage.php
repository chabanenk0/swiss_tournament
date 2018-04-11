<?php

namespace App\Services;

use App\Repository\ParticipantRepository;

class SwissTournamentManage
{
    private $participantRepository;

    public function  __construct(ParticipantRepository $participantRepository)
    {
        $this->participantRepository = $participantRepository;
    }

    public function getParticipantsDataByTournamentId($tournamentId)
    {
        $participants = $this->participantRepository->findBy(['tournament' => $tournamentId]);

        return $participants;
    }
}