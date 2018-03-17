<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Player;
use App\Entity\Tournament;
use App\Form\Type\AddParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TournamentController extends Controller
{
    public function showPlayers(Request $request, $title)
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();

        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findBy(['title' => $title]);
        foreach ($tournament as $item) {
            $tournamentId = $item->getId();
            $tournament = $item;
        }
        $participants = $this->getDoctrine()->getRepository(Participant::class)->findBy(['tournament' => $tournamentId]);
        $form = $this->createForm(AddParticipantType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playerId = $request->request->get('player_id');
            $player = $this->getDoctrine()->getRepository(Player::class)->find($playerId);
            $participant = new Participant();
            $participant->setPlayer($player);
            $participant->setTournament($tournament);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

        }

        return $this->render('admin/players.html.twig', [
            'participants' => $participants,
            'players' => $players,
            'form' => $form->createView(),
        ]);
    }

}
