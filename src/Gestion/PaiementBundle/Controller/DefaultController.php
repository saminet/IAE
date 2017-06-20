<?php

namespace Gestion\PaiementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gestion\PaiementBundle\Entity\PaiementEnseignant;
use Gestion\PaiementBundle\Form\PaiementEnseignantType;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionPaiementBundle:Default:index.html.twig');
    }

}
