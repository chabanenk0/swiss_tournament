<?php

namespace App\Controller;

use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function index()
    {
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
