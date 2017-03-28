<?php

namespace Gestion\MatiereBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionMatiereBundle:Default:index.html.twig');
    }
}
