<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Player;
use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TournamentController extends Controller
{
    public function showPlayers($title)
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findBy(['title' => $title]);
        foreach ($tournament as $item) {
            $tournamentId = $item->getId();
        }
        $participants = $this->getDoctrine()->getRepository(Participant::class)->findBy(['tournament' => $tournamentId]);

        return $this->render('admin/players.html.twig', [
            'participants' => $participants,
            'players' => $players,
        ]);
    }

}
