<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function index()
    {
        return $this->render('base.html.twig');
    }

}
