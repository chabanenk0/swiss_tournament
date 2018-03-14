<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TournamentController extends Controller
{
    public function showPlayers()
    {
        return $this->render('admin/players.html.twig');
    }

}
