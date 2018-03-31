<?php

namespace App\Controller;

use App\Entity\Round;
use App\Entity\Tournament;
use App\Services\SwissPairingSystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function index()
    {
                /** @var PairingSystemProvider $pairingSystemProvider */
        $pairingSystemProvider = $this->get(PairingSystemProvider::class);
        $pairingSystems = $pairingSystemProvider->getPairingSystemsNamesAndCodes();
        var_dump($pairingSystems);
        $tournament = new Tournament();
        $tournament->setPairingSystem(SwissPairingSystem::CODE);
//        $roundRobinSystem = $pairingSystemProvider->getPairingSystemByTournament($tournament);
        $roundRobinSystem = $pairingSystemProvider->getPairingSystemByCode($tournament->getPairingSystem());
        $participants = $this->createParticipants();
        $round = new Round();
        $round->setTournament();
        $pairs = $roundRobinSystem->doPairing($tournament, $round, $participants);
        /** @var RoundResult $pair */
        foreach ($pairs as $pair) {
            $whiteName = $pair->getWhiteParticipant()->getPlayer()->getLastName();
            $blackName = $pair->getBlackParticipant()->getPlayer()->getLastName();
            var_dump($whiteName . ' - ' . $blackName);
        }
        exit;

        return $this->render('base.html.twig');
    }

    public function showAllTournamentsAction($statusSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App:Tournament');
        if ('all' === $statusSlug) {
            $tournaments = $repo->findAll();
        } else {
            $status = Tournament::STATUS_SLUGS[$statusSlug];
            $tournaments = $repo->findBy(['status' => $status]);
        }

        return $this->render('tournament/show_all.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }
}
