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
    public function addPlayersTournamentAction(Request $request, $id)
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();
        $formOptions = [];
        foreach ($players as $player) {
            $formOptions[$player->getFirstName()." ".$player->getLastName()] = $player->getId();
        }

        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($id);
        $tournamentId = $tournament->getId();
        $participants = $this->getDoctrine()->getRepository(Participant::class)->findBy(['tournament' => $tournamentId]);

        $form = $this->createForm(AddParticipantType::class, null, ['data' => $formOptions]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playerId = $request->request->get('add_participant');
            $player = $this->getDoctrine()->getRepository(Player::class)->find($playerId['Players']);
            $participant = new Participant();
            $participant->setPlayer($player);
            $participant->setTournament($tournament);
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();
        }

        return $this->render('admin/players.html.twig', [
            'players' => $players,
            'participants' => $participants,
            'form' => $form->createView(),
        ]);
    }

}
