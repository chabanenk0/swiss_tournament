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
use App\Form\Type\RoundResultType;
use App\Form\Type\SwissResultType;
use App\Repository\ParticipantRepository;
use App\Repository\RoundResultRepository;
use App\Repository\TournamentRepository;
use App\Services\PairingSystemProvider;
use App\Services\RoundRobinPairingSystem;
use App\Services\SwissTournamentManager;
use App\Services\SwissPairingSystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Services\RoundTournamentManager;

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
        SwissTournamentManager $swissTournamentManager,
        RoundTournamentManager $roundTournamentManager
    ) {
        $this->tournamentRepository = $tournamentRepository;
        $this->participantRepository = $participantRepository;
        $this->pairingSystemProvider = $pairingSystemProvider;
        $this->swissTournamentManager = $swissTournamentManager;
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
            ->getParticipantsByTournamentAndMinimalOrder($tournament, $participant->getParticipantOrder());

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            $participant->setParticipantOrder($participant->getParticipantOrder() - 1);
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

        /** @var RoundResultRepository $roundResultsRepository */
        $roundResultsRepository = $em->getRepository(RoundResult::class);
        $roundResults = $roundResultsRepository->findBy(['tournament' => $tournament]);

        try {
            $pairingSystem = $this->pairingSystemProvider->getPairingSystemByTournament($tournament);
        } catch (UndefinedPairSystemCode $e) {
            throw $e;
        }

        $round = new Round();
        $round->setTournament($tournament);
        $em->persist($round);
        $pairs = $pairingSystem->doPairing($tournament, $round, $participants, $roundResults);

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
        return !($tournament->getStatus() === Tournament::STATUS_COMPLETED)
            || (
                $tournament->getPairingSystem() === RoundRobinPairingSystem::CODE
                && $tournament->getStatus() === Tournament::STATUS_PLANNED
            );
    }

    /**
     * @param Request $request
     * @param $tournamentId
     * @return \Symfony\Component\HttpFoundation\Response\
     */
    public function swissTournamentResultsAction(Request $request, $tournamentId)
    {
        if ($request->isMethod('POST')) {
            $roundResultId = $request->get('id');
            dump($_REQUEST);
            $roundResult = $this->getDoctrine()->getRepository(RoundResult::class)->findOneById($roundResultId);

            $form = $this->createForm(SwissResultType::class, $roundResult);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            }
        }

        /** @var Participant[] $participants */
        $participants = $this->participantRepository->findBy(['tournament' => $tournamentId]);
        /** @var RoundResult[] $roundResults */
        $roundResults = $this->getDoctrine()->getRepository(RoundResult::class)->findBy(['tournament' => $tournamentId]);
        $roundsData = [];

        foreach ($roundResults as $roundResult) {
            $round = $roundResult->getRound();
            $roundNumber = $round->getNumber();
            if (!array_key_exists($roundNumber, $roundsData)) {
                $roundsData[$roundNumber] = [
                    'round' => $round,
                    'results' => []
                ];
            }

            $blackParticipant = $roundResult->getBlackParticipant();
            $whiteParticipant = $roundResult->getWhiteParticipant();

            $roundResultFormView = $this->createForm(RoundResultType::class, $roundResult)
                ->add('Add', SubmitType::class, [
                    'label' => 'Save',
                ])->createView();

            $roundsData[$roundNumber]['results'][$blackParticipant->getParticipantOrder()] = [
                'partner' => $whiteParticipant,
                'result' => $roundResult,
                'form' => $roundResultFormView
            ];
            $roundsData[$roundNumber]['results'][$whiteParticipant->getParticipantOrder()] = [
                'partner' => $blackParticipant,
                'result' => $roundResult,
                'form' => $roundResultFormView
            ];
        }

        return $this->render('admin/swiss_system_results.html.twig', [
            'participants' => $participants,
            'rounds_data' => $roundsData,
            'tournament_id' => $tournamentId
        ]);
    }

    public function swissRoundResultsAction(Request $request, $tournamentId, $roundId)
    {
        $form = $this->createForm(SwissResultType::class);
        $form->handleRequest($request);

        /** @var Participant[] $participants */
        $participants = $this->participantRepository->findBy(['tournament' => $tournamentId]);
        /** @var RoundResult[] $roundResults */
        $roundResults = $this->getDoctrine()->getRepository(RoundResult::class)->findBy(['round' => $roundId]);

        if ($form->isSubmitted() && $form->isValid()) {
            $f = $form->getData();
//            print_r($f['round']);exit;
            $roundResult = $this->getDoctrine()->getRepository(RoundResult::class)->findOneBy([
                'round' => $roundId,
                'whiteParticipant' => $f['round'],
            ]);
            $roundResult->setResult($f['result']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($roundResult);
            $em->flush();
        }

        return $this->render('admin/swiss_system_round_results.html.twig', [
            'round_id' => $roundId,
            'tournament_id' => $tournamentId,
            'participants' => $participants,
            'form' => $form,
            'round_results' => $roundResults,
        ]);
    }

    public function getTournamentResultsForRoundSystemAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $participants = $this->participantRepository->findBy(['tournament' => $id]);
        $tournament = $this->tournamentRepository->findOneBy(['id' => $id]);

        $roundResults = $em->getRepository(RoundResult::class)->findBy(
            ['tournament' => $tournament]
        );
        $groupedRoundResults = $this->getGroupedRoundResults($roundResults);

        return $this->render('admin/round_system_results.html.twig', [
            'tournament' => $tournament,
            'participants' => $participants,
            'round_results' => $groupedRoundResults,
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
                'participantTwoId' => $participantTwoId,
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('round_system_tournament_results', [
                'id' => $roundResultTwo->getTournament()->getId()
            ]);
        }
        return $this->render('admin/form/round_result.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getGroupedRoundResults($roundResults)
    {
        $groupedRoundResults = [];

        foreach ($roundResults as $roundResult) {
            /** @var RoundResult $roundResult */
            $whiteId = $roundResult->getWhiteParticipant()->getId();
            $blackId = $roundResult->getBlackParticipant()->getId();

            if (!array_key_exists($whiteId, $groupedRoundResults)) {
                $groupedRoundResults[$whiteId] = [];
            }
            $groupedRoundResults[$whiteId][$blackId] = $roundResult;
        }
        return $groupedRoundResults;
    }
}
