<?php

namespace GestionPreinscriptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionAdminBundle:Default:index.html.twig');
    }
}
