<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Round;
use App\Entity\Tournament;
use App\Exceptions\UndefinedPairSystemCode;
use App\Form\Type\DeleteType;
use App\Form\Type\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\TournamentRepository;
use App\Services\PairingSystemProvider;
use App\Services\RoundRobinPairingSystem;
use App\Services\SwissPairingSystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class TournamentController extends Controller
{
    /**
     * @var TournamentRepository
     */
    private $tournamentRepository;
    /**
     * @var ParticipantRepository
     */
    private $participantRepository;
    /**
     * @var PairingSystemProvider
     */
    private $pairingSystemProvider;

    public function __construct(
        TournamentRepository $tournamentRepository,
        ParticipantRepository $participantRepository,
        PairingSystemProvider $pairingSystemProvider
    ) {
        $this->tournamentRepository = $tournamentRepository;
        $this->participantRepository = $participantRepository;
        $this->pairingSystemProvider = $pairingSystemProvider;
    }

    public function addPlayersTournamentAction(Request $request, $id)
    {
        /** @var Tournament $tournament */
        $tournament = $this->tournamentRepository->find($id);
        /** @var Participant[] $participants */
        $participants = $this->participantRepository->findBy(['tournament' => $id]);

        $participant = new Participant();
        $participant->setTournament($tournament);

        $deleteForm = $this->createForm(DeleteType::class);

        $deleteForm->handleRequest($request);
        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $data = $deleteForm->getData();
            return $this->redirectToRoute('delete_player_tournament', ['participantId' => $data['id']]);
        }


        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $maxOrder = array_reduce($participants, function ($maxOrder, $participant) {
                return max($maxOrder, $participant->getParticpantOrder());
            }, 0);

            $participant->setParticipantOrder($maxOrder + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('add_players_tournament', ['id' => $id]);
        }

        return $this->render('admin/players.html.twig', [
            'participants' => $participants,
            'form' => $form->createView(),
            'del_form' => $deleteForm,
            'tournament' => $tournament,
            'can_generate_round' => $this->canGenerateRound($tournament)
        ]);
    }

    public function deletePlayerTournamentAction(Request $request, $participantId)
    {
        $participant = $this->getDoctrine()->getRepository(Participant::class)->find($participantId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($participant);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param Tournament $tournament
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws UndefinedPairSystemCode
     */
    public function generatePairsAction(Request $request, Tournament $tournament = null)
    {
        if ($tournament === null) {
            throw new Exception('No tournament with id ' . $request->get('id'));
        }
        $em = $this->getDoctrine()->getManager();
        $participants = $this->participantRepository->findBy(['tournament' => $tournament]);

        if (!$this->canGenerateRound($tournament)) {
            throw new Exception('Cannot generate next round');
        };

        $tournament->setStatus(Tournament::STATUS_IN_PROGRESS);

        try {
            $pairingSystem = $this->pairingSystemProvider->getPairingSystemByTournament($tournament);
        } catch (UndefinedPairSystemCode $e) {
            throw $e;
        }

        $round = new Round();
        $em->persist($round);

        $pairs = $pairingSystem->doPairing($tournament, $round, $participants);

        foreach ($pairs as $pair) {
            $em->persist($pair);
        }
        $em->flush();

        return $this->redirectToRoute('add_players_tournament', ['id' => $tournament->getId()]);
    }

    /**
     * @param Tournament $tournament
     * @return bool
     */
    private function canGenerateRound(Tournament $tournament): bool
    {
        /** @var Tournament $tournament */
        return !$tournament->getStatus() === Tournament::STATUS_COMPLETED
            || (
                $tournament->getPairingSystem() === RoundRobinPairingSystem::CODE
                && $tournament->getStatus() === Tournament::STATUS_PLANNED
            );
    }

    public function generateNextRoundAction(Request $request)
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
    }
}
