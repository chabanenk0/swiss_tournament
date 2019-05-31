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
}
