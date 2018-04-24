<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Round;
use App\Entity\RoundResult;
use App\Entity\Tournament;
use App\Exceptions\UndefinedPairSystemCode;
use App\Form\Type\DeleteType;
use App\Form\Type\PairRoundResultType;
use App\Form\Type\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\TournamentRepository;
use App\Services\PairingSystemProvider;
use App\Services\RoundRobinPairingSystem;
use App\Services\RoundTournamentManager;
use App\Services\SwissPairingSystem;
use App\Services\SwissTournamentManage;
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


    private $swissTournamentManage;
    /**
     * @var RoundTournamentManager
     */
    private $roundTournamentManager;

    public function __construct(
        TournamentRepository $tournamentRepository,
        ParticipantRepository $participantRepository,
        PairingSystemProvider $pairingSystemProvider,
        SwissTournamentManage $swissTournamentManage,
        RoundTournamentManager $roundTournamentManager
    ) {
        $this->tournamentRepository = $tournamentRepository;
        $this->participantRepository = $participantRepository;
        $this->pairingSystemProvider = $pairingSystemProvider;
        $this->swissTournamentManage = $swissTournamentManage;
        $this->roundTournamentManager = $roundTournamentManager;
    }

    public function addPlayersTournamentAction(Request $request, $id)
    {
        /** @var Tournament $tournament */
        $tournament = $this->tournamentRepository->find($id);
        if ($tournament->getStatus() == Tournament::STATUS_IN_PROGRESS &&
            $tournament->getPairingSystem() === SwissPairingSystem::CODE) {
            return $this->redirectToRoute('set_tournament_results', [
                'tournamentId' => $id,
            ]);
        } elseif ($tournament->getStatus() == Tournament::STATUS_IN_PROGRESS &&
            $tournament->getPairingSystem() === RoundRobinPairingSystem::CODE) {
            return $this->redirectToRoute('round_system_tournament_results', [
                'id' => $id]);
        }

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
                return max($maxOrder, $participant->getParticipantOrder());
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

        $tournament = $participant->getTournament();
        $participants = $em->getRepository(Participant::class)
            ->getParticipantsByTournamentAndMinimalOrder($tournament, $participant->getParticpantOrder());

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            $participant->setParticipantOrder($participant->getParticpantOrder() - 1);
        }

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
        $round->setTournament($tournament);
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
        return $tournament->getStatus() === Tournament::STATUS_IN_PROGRESS;
    }

    public function setSwissTournamentResultsAction(SwissTournamentManage $swissTournamentManage, $tournamentId)
    {
        $participants = $this->swissTournamentManage->getParticipantsDataByTournamentId($tournamentId);

        return $this->render('admin/save_results.html.twig', [
            'participants' => $participants,
        ]);
    }

    public function getTournamentResultsForRoundSystemAction($id)
    {
        $participants = $this->participantRepository->findBy(['tournament' => $id]);
        $tournament = $this->tournamentRepository->findOneBy(['id' => $id]);

        return $this->render('admin/round_system_results.html.twig', [
            'tournament' => $tournament,
            'participants' => $participants,
        ]);
    }

    public function setResultsForRoundSystemAction(Request $request, $participantOneId, $participantTwoId)
    {
        $em = $this->getDoctrine()->getManager();

        $roundResultOne = $em->getRepository(RoundResult::class)
            ->findOneBy(['whiteParticipant' => $participantOneId, 'blackParticipant' => $participantTwoId]);
        $roundResultTwo = $em->getRepository(RoundResult::class)
            ->findOneBy(['whiteParticipant' => $participantTwoId, 'blackParticipant' => $participantOneId]);

        $participants = [$roundResultOne, $roundResultTwo];

        $form = $this->createForm(PairRoundResultType::class, $participants, [
            'action' => $this->generateUrl('set_round_result', [
                'participantOneId' => $participantOneId,
                'participantTwoId' => $participantTwoId
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('round_system_tournament_results', ['id' => $roundResultTwo->getTournament()->getId()]);
        }
        return $this->render('admin/form/round_result.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
