<?php

namespace GestionPreinscriptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionPreinscriptionBundle:Default:index.html.twig');
    }
}
