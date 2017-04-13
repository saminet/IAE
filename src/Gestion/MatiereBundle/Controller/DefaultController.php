<?php

namespace Gestion\MatiereBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Gestion\MatiereBundle\Entity\Matiere;

class DefaultController extends Controller
{

    public function listMatiereAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $matieres = $em->getRepository('GestionMatiereBundle:Matiere')->findAll();

        return $this->container->get('templating')->renderResponse('GestionMatiereBundle:Default:listeMatiere.html.twig',array(
                'matieres' => $matieres)
        );
    }

    public function ajouterMatiereAction()
    {
        return $this->render('GestionMatiereBundle:Default:ajouterMatiere.html.twig');
    }

    public function AddMatiereAction(Request $request)
    {
        $nomMatiere=$request->get('nomMatiere');
        $coefficient=$request->get('coefficient');
        $credit=$request->get('credit');
        $matieres= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();

        //sauvegarde dans la base des donnée,table profil
        $matiere = new Matiere();
        $matiere->setNomMatiere($nomMatiere);
        $matiere->setCoefficient($coefficient);
        $matiere->setCredit($credit);
        //sauvegarde des idprofil dans chaque ligne de boucle dans acces
        $em = $this->getDoctrine()->getManager();
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($matiere);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        $this->addFlash("notice",$nomMatiere." a été ajouté avec success !");

        return $this->render('GestionMatiereBundle:Default:listeMatiere.html.twig', array('matieres'=>$matieres ));

    }


    public function modifierMatiereAction($id)
    {
        $matiere= $this->getDoctrine()->getRepository('GestionMatiereBundle:Matiere')->find($id);
        return $this->render('GestionMatiereBundle:Default:modifierMatiere.html.twig', array('matieres'=>$matiere ));

    }

    public function deleteMatiereAction($id)
    {
        $something = $this->getDoctrine()
            ->getRepository('GestionMatiereBundle:Matiere')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($something);
        $em->flush();

        $matiere= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        return $this->render('GestionMatiereBundle:Default:listeMatiere.html.twig');

    }

    public function ValiderModificationAction(Request $request)
    {
        $matiereID=$request->get('idMatiere');
        $NomMatiere=$request->get('nomMatiere');
        $Coefficient=$request->get('coefficient');
        $Credit=$request->get('credit');

        $em = $this->getDoctrine()->getManager();
        $matiere = $em->getRepository('GestionMatiereBundle:Matiere')->find($matiereID);
        $matiere->setNomMatiere($NomMatiere);
        $matiere->setCoefficient($Coefficient);
        $matiere->setCredit($Credit);
        $em->flush();



        $matieree= $this->getDoctrine()->getEntityManager()->getRepository('GestionMatiereBundle:Matiere')->findAll();
        return $this->render('GestionMatiereBundle:Default:listeMatiere.html.twig', array('matieres'=>$matieree ));

    }
}
