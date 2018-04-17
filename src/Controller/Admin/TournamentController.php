<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Tournament;
use App\Form\Type\DeleteType;
use App\Form\Type\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\TournamentRepository;
use App\Services\SwissTournamentManage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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


    private $swissTournamentManage;

    public function __construct(
        TournamentRepository $tournamentRepository,
        ParticipantRepository $participantRepository,
        SwissTournamentManage $swissTournamentManage
    )
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->participantRepository = $participantRepository;
        $this->swissTournamentManage = $swissTournamentManage;
    }

    public function addPlayersTournamentAction(Request $request, $id)
    {
        /** @var Tournament $tournament */
        $tournament = $this->tournamentRepository->find($id);
        if ($tournament->getStatus() == Tournament::STATUS_IN_PROGRESS) {
            return $this->redirectToRoute('set_tournament_results', [
                'tournamentId' => $id,
            ]);
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

    public function setSwissTournamentResultsAction(SwissTournamentManage $swissTournamentManage, $tournamentId)
    {

        $participants = $this->swissTournamentManage->getParticipantsDataByTournamentId($tournamentId);

        return $this->render('admin/save_results.html.twig', [
            'participants' => $participants,
        ]);
    }
}

