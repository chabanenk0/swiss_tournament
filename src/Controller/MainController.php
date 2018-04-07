<?php

namespace App\Controller;

use App\Entity\Tournament;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    const MAX_COUNT_PER_PAGE = 3;

    public function index()
    {
        return $this->render('base.html.twig');
    }

    public function showAllTournamentsAction($statusSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tournamentRepository = $em->getRepository('App:Tournament');

        if ('all' === $statusSlug) {
            $query = $tournamentRepository
                ->createQueryBuilder('t');
        } else {
            $status = Tournament::STATUS_SLUGS[$statusSlug];
            $query = $tournamentRepository
                ->createQueryBuilder('t')
                ->where('t.status = :status')
                ->setParameter('status', $status);
        }

        $adapter = new DoctrineORMAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_COUNT_PER_PAGE);

        $currentPage = $request->get('page') ?: 1;
        $pagerfanta->setCurrentPage($currentPage);

        return $this->render('tournament/show_all.html.twig', [
            'tournaments_pager' => $pagerfanta
        ]);
    }
}
